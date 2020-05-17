<?php

namespace Pcxpress\Unifaun\Model\Order\Pdf\Proforma;


abstract class ProformaAbstract extends \Magento\Sales\Model\Order\Pdf\AbstractPdf
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentHelper;

    /**
     * @var \Magento\Framework\Code\NameBuilder
     */
    protected $frameworkNameBuilderHelper;

    /**
     * @var \Magento\Shipping\Model\Config
     */
    protected $shippingConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Framework\Code\NameBuilder $frameworkNameBuilderHelper,
        \Magento\Shipping\Model\Config $shippingConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->paymentHelper = $paymentHelper;
        $this->frameworkNameBuilderHelper = $frameworkNameBuilderHelper;
        $this->shippingConfig = $shippingConfig;
    }

    /**
     * Insert order to pdf page
     *
     * @param \Zend_Pdf_Page $page
     * @param \Magento\Sales\Model\Order $obj
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @param bool $putOrderId
     */
    protected function insertOrder(&$page, $obj, $putOrderId = true)
    {
        if ($obj instanceof \Magento\Sales\Model\Order) {
            $shipment = null;
            $order = $obj;
        } elseif ($obj instanceof \Magento\Sales\Model\Order\Shipment) {
            $shipment = $obj;
            $order = $shipment->getOrder();
        } elseif ($obj instanceof \Magento\Sales\Model\Order\Invoice) {
            $invoice = $obj;
            $order = $invoice->getOrder();
        }

        $this->y = $this->y ? $this->y : 815;
        $top = $this->y;

        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0.45));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.45));
        $page->drawRectangle(25, $top, 570, $top - 75);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
        $this->setDocHeaderCoordinates(array(25, $top, 570, $top - 55));
        $this->_setFontRegular($page, 10);

        if ($putOrderId) {
            $page->drawText(
                __('Order # ') . $order->getRealOrderId(), 35, ($top -= 30), 'UTF-8'
            );
        }

        $page->drawText(
            __('Invoice Date: ') . Mage::helper('core')->formatDate(
                $invoice->getCreatedAt(), 'medium', false
            ),
            35,
            ($top -= 15),
            'UTF-8'
        );

        $page->drawText(
            __('Order Date: ') . Mage::helper('core')->formatDate(
                $order->getCreatedAtStoreDate(), 'medium', false
            ),
            35,
            ($top -= 15),
            'UTF-8'
        );

        $top -= 10;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $top, 275, ($top - 25));
        $page->drawRectangle(275, $top, 570, ($top - 25));

        /* Calculate blocks info */

        /* Seller Store Address */
        $sellerAddress = array();
        $sellerAddress[] = $this->scopeConfig->getValue('general/store_information/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());
        $sellerAddress[] = nl2br($this->scopeConfig->getValue('general/store_information/address', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId()));
        $storeCountry = $this->scopeConfig->getValue('general/store_information/merchant_country', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());
        $sellerAddress[] = Mage::app()->getLocale()->getCountryTranslation($storeCountry, $order->getStoreId());
        $sellerAddress = implode('|', $sellerAddress);
        $sellerAddress = $this->_formatAddress($sellerAddress);

        $storeVat = 'VAT: ' . $this->scopeConfig->getValue('general/store_information/merchant_vat_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());
        $sellerAddress[] = $storeVat;

        /* Billing Address */
        $billingAddress = $this->_formatAddress($order->getBillingAddress()->format('pdf'));

        /* Payment */
        $paymentInfo = $this->paymentHelper->getInfoBlock($order->getPayment())
            ->setIsSecureMode(true)
            ->toPdf();
        $paymentInfo = htmlspecialchars_decode($paymentInfo, ENT_QUOTES);
        $payment = explode('{{pdf_row_separator}}', $paymentInfo);
        foreach ($payment as $key => $value) {
            if (strip_tags(trim($value)) == '') {
                unset($payment[$key]);
            }
        }
        reset($payment);

        /* Shipping Address and Method */
        if (!$order->getIsVirtual()) {
            $shippingMethod = $order->getShippingDescription();
        }

        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontBold($page, 12);
        $page->drawText(__('Seller:'), 35, ($top - 15), 'UTF-8');

        if (!$order->getIsVirtual()) {
            $page->drawText(__('Buyer:'), 285, ($top - 15), 'UTF-8');
        } else {
            $page->drawText(__('Payment Method:'), 285, ($top - 15), 'UTF-8');
        }

        $addressesHeight = $this->_calcAddressHeight($billingAddress);
        if (isset($sellerAddress)) {
            $addressesHeight = max($addressesHeight, $this->_calcAddressHeight($sellerAddress));
        }

        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
        $page->drawRectangle(25, ($top - 25), 570, $top - 33 - $addressesHeight);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontRegular($page, 10);
        $this->y = $top - 40;
        $addressesStartY = $this->y;

        foreach ($sellerAddress as $value) {
            if ($value !== '') {
                $text = array();
                foreach ($this->frameworkNameBuilderHelper->str_split($value, 45, true, true) as $_value) {
                    $text[] = $_value;
                }
                foreach ($text as $part) {
                    $page->drawText(strip_tags(ltrim($part)), 35, $this->y, 'UTF-8');
                    $this->y -= 15;
                }
            }
        }

        $addressesEndY = $this->y;

        if (!$order->getIsVirtual()) {
            $this->y = $addressesStartY;
            foreach ($billingAddress as $value) {
                if ($value !== '') {
                    $text = array();
                    foreach ($this->frameworkNameBuilderHelper->str_split($value, 45, true, true) as $_value) {
                        $text[] = $_value;
                    }
                    foreach ($text as $part) {
                        $page->drawText(strip_tags(ltrim($part)), 285, $this->y, 'UTF-8');
                        $this->y -= 15;
                    }
                }
            }

            $addressesEndY = min($addressesEndY, $this->y);
            $this->y = $addressesEndY;

            $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
            $page->setLineWidth(0.5);
            $page->drawRectangle(25, $this->y, 275, $this->y - 25);
            $page->drawRectangle(275, $this->y, 570, $this->y - 25);

            $this->y -= 15;
            $this->_setFontBold($page, 12);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
            $page->drawText(__('Payment Method'), 35, $this->y, 'UTF-8');
            $page->drawText(__('Shipping Information:'), 285, $this->y, 'UTF-8');

            $this->y -= 10;
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));

            $this->_setFontRegular($page, 10);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $paymentLeft = 35;
            $yPayments = $this->y - 15;
        } else {
            $yPayments = $addressesStartY;
            $paymentLeft = 285;
        }

        foreach ($payment as $value) {
            if (trim($value) != '') {
                //Printing "Payment Method" lines
                $value = preg_replace('/<br[^>]*>/i', "\n", $value);
                foreach ($this->frameworkNameBuilderHelper->str_split($value, 45, true, true) as $_value) {
                    $page->drawText(strip_tags(trim($_value)), $paymentLeft, $yPayments, 'UTF-8');
                    $yPayments -= 15;
                }
            }
        }

        if ($order->getIsVirtual()) {
            // replacement of Shipments-Payments rectangle block
            $yPayments = min($addressesEndY, $yPayments);
            $page->drawLine(25, ($top - 25), 25, $yPayments);
            $page->drawLine(570, ($top - 25), 570, $yPayments);
            $page->drawLine(25, $yPayments, 570, $yPayments);

            $this->y = $yPayments - 15;
        } else {
            $topMargin = 10;
            $methodStartY = $this->y;
            $this->y -= 15;

            foreach ($this->frameworkNameBuilderHelper->str_split($shippingMethod, 45, true, true) as $_value) {
                $page->drawText(strip_tags(trim($_value)), 285, $this->y, 'UTF-8');
                $this->y -= 10;
            }

            $yShipments = $this->y;
            $totalShippingChargesText = __('Total Shipping Charges') . ': '
                . $order->formatPriceTxt($order->getShippingAmount());
            $page->drawText($totalShippingChargesText, 285, $yShipments - $topMargin, 'UTF-8');
            $yShipments -= $topMargin + 10;

            $grossWeight = $order->getWeight() ? $order->getWeight() : 0;
            $grossWeightText = __('Gross Weight') . ': ' . strip_tags(trim(ceil($grossWeight) . ' kg'));
            $page->drawText($grossWeightText, 285, $yShipments - $topMargin, 'UTF-8');
            $yShipments -= $topMargin + 10;


            $deliveryTerms = $this->scopeConfig->getValue('carriers/unifaun/delivery_terms', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());
            if (!$deliveryTerms) {
                $deliveryTerms = __('None');
            }
            $deliveryTermsText = __('Delivery Terms') . ': ' . $deliveryTerms;
            $page->drawText($deliveryTermsText, 285, $yShipments - $topMargin, 'UTF-8');
            $yShipments -= $topMargin + 10;

            $tracks = array();

            if ($obj instanceof \Magento\Sales\Model\Order\Shipment) {
                $tracks = $shipment->getAllTracks();
            }
            if (count($tracks)) {
                $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
                $page->setLineWidth(0.5);
                $page->drawRectangle(285, $yShipments, 510, $yShipments - 10);
                $page->drawLine(400, $yShipments, 400, $yShipments - 10);
                //$page->drawLine(510, $yShipments, 510, $yShipments - 10);

                $this->_setFontRegular($page, 9);
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
                //$page->drawText(Mage::helper('sales')->__('Carrier'), 290, $yShipments - 7 , 'UTF-8');
                $page->drawText(__('Title'), 290, $yShipments - 7, 'UTF-8');
                $page->drawText(__('Number'), 410, $yShipments - 7, 'UTF-8');

                $yShipments -= 20;
                $this->_setFontRegular($page, 8);
                foreach ($tracks as $track) {

                    $CarrierCode = $track->getCarrierCode();
                    if ($CarrierCode != 'custom') {
                        $carrier = $this->shippingConfig->getCarrierInstance($CarrierCode);
                        $carrierTitle = $carrier->getConfigData('title');
                    } else {
                        $carrierTitle = __('Custom Value');
                    }

                    //$truncatedCarrierTitle = substr($carrierTitle, 0, 35) . (strlen($carrierTitle) > 35 ? '...' : '');
                    $maxTitleLen = 45;
                    $endOfTitle = strlen($track->getTitle()) > $maxTitleLen ? '...' : '';
                    $truncatedTitle = substr($track->getTitle(), 0, $maxTitleLen) . $endOfTitle;
                    //$page->drawText($truncatedCarrierTitle, 285, $yShipments , 'UTF-8');
                    $page->drawText($truncatedTitle, 292, $yShipments, 'UTF-8');
                    $page->drawText($track->getNumber(), 410, $yShipments, 'UTF-8');
                    $yShipments -= $topMargin - 5;
                }
            } else {
                $yShipments -= $topMargin - 5;
            }

            $currentY = min($yPayments, $yShipments);

            // replacement of Shipments-Payments rectangle block
            $page->drawLine(25, $methodStartY, 25, $currentY); //left
            $page->drawLine(25, $currentY, 570, $currentY); //bottom
            $page->drawLine(570, $currentY, 570, $methodStartY); //right

            $this->y = $currentY;
            $this->y -= 15;
        }
    }
}
