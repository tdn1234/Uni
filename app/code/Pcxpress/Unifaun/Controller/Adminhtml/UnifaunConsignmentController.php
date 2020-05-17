<?php

namespace Pcxpress\Unifaun\Controller\Adminhtml\Unifaun;


/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
class UnifaunConsignmentController extends \Magento\Backend\App\Action

{

    /* protected function _isAllowed()

     {

         return Mage::getSingleton('admin/session')->isAllowed('sales/unifaun/unifaun_consignments');

     }*/

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
     * @var \Pcxpress\Unifaun\Helper\Consignment
     */
    protected $unifaunConsignmentHelper;

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
     * @var
     */
protected $;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Sales\Model\Order\ShipmentFactory $salesOrderShipmentFactory,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Sales\Model\OrderFactory $salesOrderFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Helper\Consignment $unifaunConsignmentHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTimeDateTime,
        \Magento\Framework\Event\ObserverFactory $observerFactory,
        \Pcxpress\Unifaun\Model\ObserverFactory $unifaunObserverFactory
    )
    {
        $this->observerFactory = $observerFactory;
        $this->unifaunObserverFactory = $unifaunObserverFactory;

        $this->request = $request;
        $this->salesOrderShipmentFactory = $salesOrderShipmentFactory;
        $this->backendHelper = $backendHelper;
        $this->backendSession = $backendSession;
        $this->salesOrderFactory = $salesOrderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->unifaunConsignmentHelper = $unifaunConsignmentHelper;
        $this->dateTimeDateTime = $dateTimeDateTime;
    }

    protected function _initAction()

    {

        $this->loadLayout()
            ->_setActiveMenu('sales/unifaun/unifaun_consignments')
            ->_addBreadcrumb(__('Pcxpress Unifaun: Consignments'), __('Consignment Manager'));


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


    public function checkBatchAction()

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

        $result = $observer->addConsignment($event, $request);

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

        $request = $this->getRequest();
        // var_dump($request->getParams());die;

        $type = $request->getParam('type');

        $consignmentHelper = $this->unifaunConsignmentHelper;

        list($filename, $document) = $consignmentHelper->printConsignment($request, $type);


        return $this->_prepareDownloadResponse(

            $filename . '-' . $this->dateTimeDateTime->date('Y-m-d_H-i-s') . '.pdf', $document->render(),

            'application/pdf'

        );

    }

    public function massPrintTypesAction()
    {

        $request = $this->getRequest();

        $types = $request->getParam('types');

        $types = explode(',', $types);


        if (count($types)) {
            foreach ($types as $type) {

                list($filename, $document) = $consignmentHelper->printConsignment($request, $type);

                $this->_prepareDownloadResponse(

                    $filename . '-' . $this->dateTimeDateTime->date('Y-m-d_H-i-s') . '.pdf', $document->render(),

                    'application/pdf'

                );
            }

        }


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

            $shipmentApi = $this->->create();

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

    public function bookAction()
    {
        $order_id = $this->getRequest()->getParam('order_id');

        $this->unifaunConsignmentHelper->bookConsignment($order_id);
    }

}