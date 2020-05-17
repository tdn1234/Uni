<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Controller\Adminhtml\Unifaun;

class UnifaunConsignmentController extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $backendAuthSession;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Sales\Model\Order\ShipmentFactory
     */
    protected $salesOrderShipmentFactory;

    /**
     * @var \Magento\Backend\Helper\Data
     */
    protected $backendHelper;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $salesOrderFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\Order\Pdf\ProformaFactory
     */
    protected $unifaunOrderPdfProformaFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTimeDateTime;

    /**
     * @var \Magento\Framework\Event\ObserverFactory
     */
    protected $observerFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\ObserverFactory
     */
    protected $unifaunObserverFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory
     */
    protected $unifaunPcxpressUnifaunFactory;


    public function __construct(
        \Magento\Backend\Model\Auth\Session $backendAuthSession,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Sales\Model\Order\ShipmentFactory $salesOrderShipmentFactory,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Sales\Model\OrderFactory $salesOrderFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Model\Order\Pdf\ProformaFactory $unifaunOrderPdfProformaFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTimeDateTime,
        \Magento\Framework\Event\ObserverFactory $observerFactory,
        \Pcxpress\Unifaun\Model\ObserverFactory $unifaunObserverFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory $unifaunPcxpressUnifaunFactory
    )
    {
        $this->observerFactory = $observerFactory;
        $this->unifaunObserverFactory = $unifaunObserverFactory;
        $this->unifaunPcxpressUnifaunFactory = $unifaunPcxpressUnifaunFactory;
//        $this-> = $;
        $this->backendAuthSession = $backendAuthSession;
        $this->request = $request;
        $this->salesOrderShipmentFactory = $salesOrderShipmentFactory;
        $this->backendHelper = $backendHelper;
        $this->backendSession = $backendSession;
        $this->salesOrderFactory = $salesOrderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunOrderPdfProformaFactory = $unifaunOrderPdfProformaFactory;
        $this->dateTimeDateTime = $dateTimeDateTime;
    }

    protected function _isAllowed()
    {
        return $this->backendAuthSession->isAllowed('sales/unifaun/unifaun_consignments');
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sales/unifaun/unifaun_consignments')
            ->_addBreadcrumb(__('Pcxpress Unifaun: Consignments'), __('Item Manager'));

        return $this;
    }

    public function execute()
    {
        $this->_initAction()->renderLayout();
    }

    public function createAction()
    {
        $request = $this->request;
        $order = null;
        $shipmentId = $request->getParam("shipment_id");
        $shipment = $this->salesOrderShipmentFactory->create()->load($shipmentId);
        /** @var $shipment Mage_Sales_Model_Order_Shipment */
        if ($shipment) {
            $event = $this->observerFactory->create(array('shipment' => $shipment));

            $this->_createConsignment($event, $request);

            // Redirect back
            Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order_shipment/view", array('shipment_id' => $shipmentId)));
            return;
        }

        $this->backendSession->addError(__('Cannot find shipment %s', $shipmentId));
        Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order_shipment/view", array('shipment_id' => $shipmentId)));
        return;
    }

    public function batchAction()
    {
        $request = $this->getRequest();
        $orderIds = $request->getParam('order_ids');

        if (!is_array($orderIds)) {
            $this->backendSession->addError(__('No valid packages'));
            return;
        }

        // Iterate over orders and create shipments
        $shipmentIds = $this->_createShipment($orderIds);

        // Redirect back
        Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order/index"));
        return;
    }

    public function batchCheckAction()
    {
        $request = $this->getRequest();
        $body = $request->getRawBody();
        $json = json_decode($body, true);
        if (!$json) {
            die("ERROR");
        }

        $orderIds = (array_key_exists('ids', $json) && is_array($json['ids'])) ? $json['ids'] : array();

        $countries = array();
        foreach ($orderIds as $orderId) {
            $order = $this->salesOrderFactory->create()->load($orderId);
            /** @var $order Mage_Sales_Model_Order */

            if ($order->isObjectNew()) {
                continue;
            }

            $shippingAddress = $order->getShippingAddress();
            if ($shippingAddress) {
                $countryId = $shippingAddress->getCountryId();
                if (!array_key_exists($countryId, $countries)) {
                    $countries[$countryId] = array();
                }

                $countries[$countryId][] = $order->getIncrementId();
            }
        }

        $storeCountry = $this->scopeConfig->getValue('general/country/default', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        header("Content-type: application/json");
        die(json_encode(array(
            'defaultCountry' => $storeCountry,
            'summary' => $countries
        )));
    }

    /**
     * Call Observer to create consignment
     * @param \Magento\Framework\Event\Observer $event
     * @param \Magento\Framework\App\Request\Http $request
     */
    protected function _createConsignment(\Magento\Framework\Event\Observer $event, \Magento\Framework\App\Request\Http $request)
    {
        $observer = $this->unifaunObserverFactory->create();
        $result = $observer->createConsignment($event, $request);
        if ($result) {
            // Everything went ok, add a success message
            $this->backendSession->addSuccess(__('Consignment created'));
            return;
        }
    }

    /**
     * Print unifaun label for one consignment
     */
    public function printAction()
    {
        $document = null;
        $request = $this->getRequest();
        $filename = 'unifaun-labels';

        $consignmentNos = $request->getParam('consignment_nos');
        $orderIds = $request->getParam('orderIds');
        $coreHelper = Mage::helper('core');
        $useProforma = $this->unifaunHelper->getUseProforma();

        if (is_string($consignmentNos)) {
            $consignmentNos = array($consignmentNos);
        }

        $unifaunConsignmentDocumentsString = null;
        if (is_array($consignmentNos) && count($consignmentNos)) {
            $unifaun = $this->unifaunPcxpressUnifaunFactory->create();
            $document = \Zend_Pdf::parse(base64_decode($unifaun->getConsignmentDocument($consignmentNos)));
        }

        if (!$document) {
            $document = new \Zend_Pdf();
            $document->pages[] = new \Zend_Pdf_Page(\Zend_Pdf_Page::SIZE_A4);
        }

        if (!is_array($orderIds)) {
            $orderIds = explode(",", $orderIds);
        }

        // When Pro Forma Invoice is activated in config...
        if ($useProforma) {
            if (is_array($orderIds) && count($orderIds)) {
                foreach ($orderIds as $orderId) {
                    $order = $this->salesOrderFactory->create()->load($orderId);

                    if ($order) {
                        $billingAddressCountryId = $order->getBillingAddress()->getCountryId();

                        // If country of the billing address is not within the EU...
                        if (!$coreHelper->isCountryInEU($billingAddressCountryId, $order->getStoreId())) {
                            $invoices = $order->getInvoiceCollection();
                            if ($invoices->getSize() == 0) {
                                $this->backendSession->addError(__('Order %s must have state "%s" before printing Pro Forma Invoice.', $orderId, ucfirst(\Magento\Sales\Model\Order::STATE_COMPLETE)));
                                Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order/index"));
                                return;
                            }

                            $sellerName = $this->scopeConfig->getValue('general/store_information/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());
                            $sellerAddress = $this->scopeConfig->getValue('general/store_information/address', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());
                            $sellerCountry = $this->scopeConfig->getValue('general/store_information/merchant_country', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());
                            $sellerVat = $this->scopeConfig->getValue('general/store_information/merchant_vat_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());

                            if (!$sellerName || !$sellerAddress || !$sellerCountry) {
                                $this->backendSession->addError(__('Merchant address is not configured properly, this is required to print Pro Forma Invoice.'));
                                Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order/index"));
                                return;
                            }

                            if (!$sellerVat) {
                                $this->backendSession->addError(__('Merchant VAT number is missing, this is required to print Pro Forma Invoice.'));
                                Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order/index"));
                                return;
                            }

                            $proformaPdf = $this->unifaunOrderPdfProformaFactory->create()->getPdf($invoices);

                            $document->pages = array_merge($document->pages, $proformaPdf->pages);
                            $filename = 'unifaun-labels-and-proforma-invoices';
                        }
                    }
                }
            }
        }

        return $this->_prepareDownloadResponse(
            $filename . '-' . $this->dateTimeDateTime->date('Y-m-d_H-i-s') . '.pdf', $document->render(),
            'application/pdf'
        );
    }

    public function massPrintAction()
    {
        $request = $this->getRequest();
        $shipmentIds = $request->getParam('shipment_ids');
        $consignmentNos = array();
        $orderIds = array();
        foreach ($shipmentIds as $shipmentId) {
            $shipment = $this->salesOrderShipmentFactory->create()->load($shipmentId);
            /** @var $shipment Mage_Sales_Model_Order_Shipment */
            if ($shipment) {
                foreach ($shipment->getAllTracks() as $track) {
                    if ($track->getNumber()) {
                        // Add consignment number to array
                        $consignmentNos[] = $track->getNumber();
                    }
                }
                $order = $shipment->getOrder();
                if ($order) {
                    $orderIds[] = $order->getId();
                }
            }

        }

        if (count($consignmentNos) && count($orderIds)) {
            return $this->_forward('print', null, null, array('consignment_nos' => $consignmentNos, 'orderIds' => $orderIds));
        }

        $this->backendSession->addError(__('An error occurred when trying to get Unifaun labels.'));
        return;
    }

    public function createAndPrintAction()
    {
        $request = $this->getRequest();
        $orderIds = $request->getParam('order_ids');

        if (!is_array($orderIds)) {
            $this->backendSession->addError(__('No valid packages'));
            return;
        }

        $shipmentIds = $this->_createShipment($orderIds);

        // Get labels from Pcxpress
        $consignmentNos = array();
        foreach ($shipmentIds as $shipmentId) {
            $shipment = $this->salesOrderShipmentFactory->create()->loadByIncrementId($shipmentId);
            /** @var $shipment Mage_Sales_Model_Order_Shipment */
            if ($shipment) {
                foreach ($shipment->getAllTracks() as $track) {
                    if ($track->getNumber()) {
                        // Add consignment number to array
                        $consignmentNos[] = $track->getNumber();
                    }
                }
            }
        }

        return $this->_forward('print', null, null, array('consignment_nos' => $consignmentNos, 'orderIds' => $orderIds));
    }

    protected function _createShipment(array $orderIds)
    {
        // Iterate over orders and create shipments
        $shipmentIds = array();
        foreach ($orderIds as $orderId) {
            $order = $this->salesOrderFactory->create()->load($orderId);
            /** @var $order Mage_Sales_Model_Order */

            if (!$order) {
                $this->backendSession->addError(__('Order %s cannot be found.', $orderId));
                continue;
            }

            if ($order->getState() == \Magento\Sales\Model\Order::STATE_COMPLETE) {
                continue;
            }

            if (!$order->canShip()) {
                $this->backendSession->addError(__('Order %s cannot be shipped.', $order->getIncrementId()));
                continue;
            }

            // Create shipment
//            $shipmentApi = new Mage_Sales_Model_Order_Shipment_Api();
            $shipmentId = $shipmentApi->create($order->getIncrementId());

            // Send mail
            $shipmentApi->sendInfo($shipmentId);

            if ($shipmentId) {
                $shipmentIds[] = $shipmentId;
                $this->backendSession->addSuccess(__('Shipment %s created.', $shipmentId));
            }
        }

        return $shipmentIds;
    }

}