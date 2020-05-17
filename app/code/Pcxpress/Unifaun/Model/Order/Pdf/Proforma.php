<?php

namespace Pcxpress\Unifaun\Model\Order\Pdf;

use Pcxpress\Unifaun\Model\Order\Pdf\Proforma\ProformaAbstract;

/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
class Proforma extends ProformaAbstract
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Draw header for item table
     *
     * @param \Zend_Pdf_Page $page
     * @return void
     */
    protected function _drawHeader(\Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 15);
        $this->y -= 10;
        $page->setFillColor(new \Zend_Pdf_Color_RGB(0, 0, 0));

        //columns headers
        $lines[0][] = array(
            'text' => __('Products'),
            'feed' => 35
        );

        $lines[0][] = array(
            'text' => __('SKU'),
            'feed' => 290,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text' => __('Qty'),
            'feed' => 435,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text' => __('Price'),
            'feed' => 360,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text' => __('Tax'),
            'feed' => 495,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text' => __('Subtotal'),
            'feed' => 565,
            'align' => 'right'
        );

        $lineBlock = array(
            'lines' => $lines,
            'height' => 5
        );

        $this->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

    /**
     * Return PDF document
     *
     * @param  array $invoices
     * @param  int $copies
     * @return \Zend_Pdf
     */
    public function getPdf($invoices = array(), $copies = 3)
    {
        if ($copies > 99) {
            $copies = 99;
        }

        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            while ($copies > 0) {
                if ($invoice->getStoreId()) {
                    Mage::app()->getLocale()->emulate($invoice->getStoreId());
                    Mage::app()->setCurrentStore($invoice->getStoreId());
                }
                $page = $this->newPage();
                $order = $invoice->getOrder();
                /* Add image */
                $this->insertLogo($page, $invoice->getStore());
                /* Add address */
                $this->insertAddress($page, $invoice->getStore());
                /* Add head */
                $this->insertOrder(
                    $page,
                    $invoice,

                    $this->scopeConfig->isSetFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId())
                );
                /* Add document text and number */
                $this->insertDocumentNumber(
                    $page,
                    __('Pro Forma Invoice # ') . $invoice->getIncrementId()
                );
                /* Add table */
                $this->_drawHeader($page);
                /* Add body */
                foreach ($invoice->getAllItems() as $item) {
                    if ($item->getOrderItem()->getParentItem()) {
                        continue;
                    }
                    /* Draw item */
                    $this->_drawItem($item, $page, $order);
                    $page = end($pdf->pages);
                }
                /* Add totals */
                $this->insertTotals($page, $invoice);
                if ($invoice->getStoreId()) {
                    Mage::app()->getLocale()->revert();
                }

                $copies--;
            }
        }
        $this->_afterGetPdf();
        return $pdf;
    }

    /**
     * Create new page and assign to PDF object
     *
     * @param  array $settings
     * @return \Zend_Pdf_Page
     */
    public function newPage(array $settings = array())
    {
        /* Add new table head */
        $page = $this->_getPdf()->newPage(\Zend_Pdf_Page::SIZE_A4);
        $this->_getPdf()->pages[] = $page;
        $this->y = 800;
        if (!empty($settings['table_header'])) {
            $this->_drawHeader($page);
        }
        return $page;
    }
}
