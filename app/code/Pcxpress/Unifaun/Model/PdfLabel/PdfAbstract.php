<?php

namespace Pcxpress\Unifaun\Model\PdfLabel;


abstract class PdfAbstract extends \Magento\Framework\DataObject
{
    const PAGE_SIZE = \Zend_Pdf_Page::SIZE_A4; // A4 Size: 595:842:
    const LINE_HEIGHT = 82; // Line height
    const FONT_NAME = \Zend_Pdf_Font::FONT_HELVETICA;
    const FONT_SIZE = 75;
    const OFFSET_X = 0; // Space between corner and the first label
    const OFFSET_Y = 39.69; // Space between corner and the first label
    const LBL_OFFSET_X = 20; // Space between label corner and text
    const LBL_OFFSET_Y = 20; // Space between label corner and text
    const LBL_WIDTH = 595; // Label width
    const LBL_HEIGHT = 742; // Label height
    const LBL_COUNT_X = 3; // Number of label columns
    const LBL_COUNT_Y = 1; // Number of label rows
    const LBL_SPACING_X = 0; // Spacing between label rows
    const LBL_SPACING_Y = 0; // Spacing between label columns
    const LBL_RECTANGLE = true;
    const LBL_RECTANGLE_BORDER_RADIUS = 2;
    const LBL_RECTANGLE_SPACING_X = 10;
    const LBL_RECTANGLE_SPACING_Y = 10;
    const LOGO_SPACING_BOTTOM = 10;
    const CM_TO_PX_RATE = 37.8;

    protected $_coordinates;
    protected $_items = array();

    protected $labelHeight = 0;
    protected $labelWidth = 0;
    protected $labelFontSize = 0;
    protected $labelLineHeight = 0;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        array $data = []
    )
    {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->scopeConfig = $scopeConfig;
        parent::__construct(
            $data
        );
    }


    protected function _construct()
    {

        $this->labelHeight = ($this->scopeConfig->getValue('carriers/' . 'unifaun' . '/label_height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) ? $this->scopeConfig->getValue('carriers/' . 'unifaun' . '/label_height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) * self::CM_TO_PX_RATE : self::LBL_HEIGHT;

        $this->labelWidth = ($this->scopeConfig->getValue('carriers/' . 'unifaun' . '/label_width', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) ? $this->scopeConfig->getValue('carriers/' . 'unifaun' . '/label_width', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) * self::CM_TO_PX_RATE : self::LBL_WIDTH;

        $this->labelFontSize = ($this->scopeConfig->getValue('carriers/' . 'unifaun' . '/label_font_size', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) ? $this->scopeConfig->getValue('carriers/' . 'unifaun' . '/label_font_size', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) : self::FONT_SIZE;
        $this->labelLineHeight = ($this->scopeConfig->getValue('carriers/' . 'unifaun' . '/label_line_height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) ? $this->scopeConfig->getValue('carriers/' . 'unifaun' . '/label_line_height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) : self::LINE_HEIGHT;

        $this->pageSize = $this->labelWidth . ':' . $this->labelHeight . ':';
        $this->labelWidth = $this->labelWidth;
        $this->labelHeight = $this->labelHeight - 30;

    }

    /**
     * Add shipment
     * @param \Magento\Sales\Model\Order\Shipment $item
     * @return \Pcxpress\Unifaun\Model\PdfLabel\Abstract
     */
    public function addShipment(\Magento\Sales\Model\Order\Shipment $item)
    {
        $this->_items[] = $item;
        return $this;
    }

    /**
     * Render the pdf and return the document as a string
     * @return string
     */
    public function render()
    {
        // Each labelset contains the maximum number of labels that will fit on each page
        $startPosition = $this->getStartPosition() ? $this->getStartPosition() : 0;
        if ($startPosition > 0) {
            $placeholders = array_fill(0, $startPosition, null);
            $items = array_merge($placeholders, $this->_items);
        } else {
            $items = $this->_items;
        }

        $labelSets = array_chunk($items, 1);
        // $labelSets = array_chunk($items, $this->_getLabelsPerPage());

        $pdf = new \Zend_Pdf();

        // Loop through all labelsets and create a page for each of them
        foreach ($labelSets as $labelSet) {
            $page = $this->_addNewPage();

            $pdf->pages[] = $page;
            foreach ($labelSet as $labelNo => $item) {
                $coord = $this->_getLabelCoordinate($labelNo);

                if (!$coord) {
                    continue; // This would be a very strange state, but however error handling is good
                }
                if ($item) {
                    $this->_addLabel($page, $coord, $item);
                }
            }
        }
        $pdf = $pdf->render();
        return $pdf;
    }

    /**
     * Render a label to page
     * @param \Zend_Pdf_Page $page
     * @param \Magento\Framework\DataObject $coordinate
     * @param \Magento\Sales\Model\Order\Shipment $item
     * @return \Pcxpress\Unifaun\Model\PdfLabel\Abstract
     */
    protected function _addLabel(\Zend_Pdf_Page $page, \Magento\Framework\DataObject $coordinate, \Magento\Sales\Model\Order\Shipment $item)
    {
        $shippingAddress = $item->getShippingAddress();
        if ($shippingAddress) {
            $shippingAddressParts = explode("|", str_replace("\n", "|", $shippingAddress->format("pdf")));

            $shippingAddressParts = $this->removeShippAddress($shippingAddressParts, $shippingAddress);

            $shippingAddressAsArray = array();
            foreach ($shippingAddressParts as $addressRow) {
                if (strlen(trim($addressRow)) == 0) {
                    continue;
                }
                $shippingAddressAsArray[] = $addressRow;
            }
        }

        // Image
        $store = $item->getStore();
        $logo = null;
        $logoName = $this->scopeConfig->getValue('carriers/' . 'unifaun' . '/logo', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
        $logo_height = 0;

        if ($logoName) {
            $logoPath = Mage::getBaseDir("media") . "/unifaun/label/logo/" . $logoName;
            if (file_exists($logoPath)) {
                $logo = \Zend_Pdf_Image::imageWithPath($logoPath);
                $pixelWidth = $logo->getPixelWidth();
                $pixelHeight = $logo->getPixelHeight();
                $logo_width = $this->labelWidth - self::LBL_OFFSET_X * 2;
                $factor = $pixelWidth / $logo_width;
                $logo_height = $pixelHeight / $factor;
                $logo_x1 = $coordinate->getX() + self::LBL_OFFSET_X;
                $logo_x2 = $logo_x1 + $logo_width;
                $logo_y1 = $coordinate->getY() + $this->labelHeight - self::LBL_OFFSET_Y - $logo_height;
                $logo_y2 = $coordinate->getY() + $this->labelHeight - self::LBL_OFFSET_Y;
            }
        }

        if ((bool)$this->scopeConfig->getValue('carriers/' . 'unifaun' . '/debug' . $store->getId(\Magento\Store\Model\ScopeInterface::SCOPE_STORE))) {
            // Label dimension
            $page->setAlpha(0.1);
            $x1 = $coordinate->getX();
            $x2 = $coordinate->getX() + $this->labelWidth;
            $y1 = $coordinate->getY();
            $y2 = $y1 + $this->labelHeight;
            $page->drawRectangle($x1, $y1, $x2, $y2);

            // Inner dimension
            $page->setAlpha(0.1);
            $x1 = $coordinate->getX() + self::LBL_OFFSET_X;
            $x2 = $coordinate->getX() + $this->labelWidth - self::LBL_OFFSET_X;
            $y1 = $coordinate->getY() + self::LBL_OFFSET_Y;
            $y2 = $coordinate->getY() + $this->labelHeight - self::LBL_OFFSET_Y;
            $page->drawRectangle($x1, $y1, $x2, $y2);

            // Content
            $page->setAlpha(0.1);
            $x1 = $coordinate->getX() + self::LBL_OFFSET_X;
            $x2 = $coordinate->getX() + $this->labelWidth - self::LBL_OFFSET_X;
            $y1 = $coordinate->getY() + self::LBL_OFFSET_Y;
            $y2 = $coordinate->getY() + $this->labelHeight - self::LBL_OFFSET_Y - $logo_height - self::LOGO_SPACING_BOTTOM;
            $page->drawRectangle($x1, $y1, $x2, $y2);

            if (self::LBL_RECTANGLE) {
                // Rounded rectangle
                $page->setAlpha(0.1);
                $x1 = $coordinate->getX() + self::LBL_OFFSET_X + self::LBL_RECTANGLE_SPACING_X;
                $x2 = $coordinate->getX() + $this->labelWidth - self::LBL_OFFSET_X - self::LBL_RECTANGLE_SPACING_X;
                $y1 = $coordinate->getY() + self::LBL_OFFSET_Y + self::LBL_RECTANGLE_SPACING_Y;
                $y2 = $coordinate->getY() + $this->labelHeight - self::LBL_OFFSET_Y - $logo_height - self::LOGO_SPACING_BOTTOM - self::LBL_RECTANGLE_SPACING_Y;
                $page->drawRectangle($x1, $y1, $x2, $y2);
            }
        }
        // END: VIEW LBL DIMENSIONS

        $y = $coordinate->getY();
        $y -= self::LBL_OFFSET_Y;

        $page->setAlpha(1);

        // Rounded rectangle
        // if (self::LBL_RECTANGLE) {
        // 	$x1 = $coordinate->getX() + self::LBL_OFFSET_X;
        // 	$x2 = $coordinate->getX() + $this->labelWidth - self::LBL_OFFSET_X;
        // 	$y1 = $coordinate->getY() + self::LBL_OFFSET_Y;
        // 	$y2 = $coordinate->getY() + $this->labelHeight - self::LBL_OFFSET_Y - $logo_height - self::LOGO_SPACING_BOTTOM;
        // 	$page->drawRoundedRectangle($x1, $y1, $x2, $y2, self::LBL_RECTANGLE_BORDER_RADIUS, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        // }

        // Draw logo
        if ($logo) {
            $page->drawImage($logo, $logo_x1, $logo_y1, $logo_x2, $logo_y2);
        }

        if (self::LBL_RECTANGLE) {
            $posX = $coordinate->getX() + self::LBL_OFFSET_X + self::LBL_RECTANGLE_SPACING_X;
            $posY = $coordinate->getY() + $this->labelHeight - self::LBL_OFFSET_Y - $logo_height - self::LOGO_SPACING_BOTTOM - self::LBL_RECTANGLE_SPACING_Y;
        } else {
            $posX = $coordinate->getX() + self::LBL_OFFSET_X;
            $posY = $coordinate->getY() + $this->labelHeight - self::LBL_OFFSET_Y - $logo_height - self::LOGO_SPACING_BOTTOM;
        }

        foreach ($shippingAddressAsArray as $value) {
            if ($value !== '') {
                $posY -= $this->labelLineHeight;
                $page->drawText(strip_tags(ltrim($value)), $posX, $posY, 'UTF-8');
            }
        }

        return $this;
    }

    /**
     * Get number of labels per page
     * @return int
     */
    protected function _getLabelsPerPage()
    {
        return self::LBL_COUNT_X * self::LBL_COUNT_Y;
    }

    /**
     * Get the label coordinate for a specific label on a page
     * @param int $labelNo
     * @return \Magento\Framework\DataObject
     */
    protected function _getLabelCoordinate($labelNo)
    {
        $coordinates = $this->_getLabelCoordinates();
        if (!array_key_exists($labelNo, $coordinates)) {
            return null;
        }
        return $coordinates[$labelNo];
    }

    /**
     * Get bottom left coordinates for all labels
     * It is much easier if we treat the paper as a grid system and calculate the coordinates
     * before start to render.
     * @return array
     */
    protected function _getLabelCoordinates()
    {
        if (!$this->_coordinates) {
            //list(, $pageHeight) = explode(":", self::PAGE_SIZE);

            $labelsPerPage = $this->_getLabelsPerPage();

            $this->_coordinates = array();
            for ($k = 0; $k < $labelsPerPage; $k++) {
                $coords = $this->dataObjectFactory->create();

                $column = $k % self::LBL_COUNT_X;
                $row = floor($k / self::LBL_COUNT_X);
                $rowInverted = self::LBL_COUNT_Y - $row - 1;

                $coords->setRow($row);
                $coords->setRowInverted($rowInverted);
                $coords->setColumn($column);

                // Coordinate X
                $positionX = self::OFFSET_X + $column * (self::LBL_SPACING_X + $this->labelWidth);
                $coords->setX($positionX);

                // Coordinate Y
                $positionY = self::OFFSET_Y + $rowInverted * (self::LBL_SPACING_Y + $this->labelHeight);
                $coords->setY($positionY);

                $this->_coordinates[$k] = $coords;
            }
        }

        return $this->_coordinates;
    }

    protected function _addNewPage()
    {
        // $page = new Zend_Pdf_Page(self::PAGE_SIZE);
        $page = new \Zend_Pdf_Page($this->pageSize);
        $page->setFont(\Zend_Pdf_Font::fontWithName(self::FONT_NAME), $this->labelFontSize);
        return $page;
    }

    protected function removeShippAddress($shippingAddressParts, $shippingAddress)
    {

        $postcode = $shippingAddress->getData('postcode');

        $fax = $shippingAddress->getData('fax');
        $telephone = $shippingAddress->getData('telephone');
        $region = $shippingAddress->getData('region');
        $country_id = $shippingAddress->getData('country_id');
        $city = $shippingAddress->getData('city');

        $country_name = Mage::app()->getLocale()->getCountryTranslation($country_id);

        foreach ($shippingAddressParts as $key => $value) {
            if (strpos($value, $postcode) !== false
                || strpos($value, $fax) !== false
                || strpos($value, $telephone) !== false
                || strpos($value, $region) !== false
                || strpos($value, $country_name) !== false
                || strpos($value, $city) !== false
            ) {
                unset($shippingAddressParts[$key]);
            }
        }
        $shippingAddressParts[] = $postcode . ', ' . rtrim($city, ',');

        return $shippingAddressParts;
    }

}