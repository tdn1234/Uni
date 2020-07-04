<?php

namespace Pcxpress\Unifaun\Model;


/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
class Observer
{


    /**
     * On shipment creat add new consignment
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @param \Magento\Framework\App\Request\Http $request
     * @throws \Exception
     * @return bool
     */


    const RETURN_CONSIGNMENT = 'return_consignment';
    protected $writeConnection;
    protected $shipment_track_table;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment\Data
     */
    protected $unifaunPcxpressUnifaunConsignmentData;

    /**
     * @var \Pcxpress\Unifaun\Model\ParcelFactory
     */
    protected $unifaunParcelFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Pacsoft\Data
     */
    protected $unifaunPacsoftDataHelper;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentFactory
     */
    protected $unifaunPcxpressUnifaunConsignmentFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory
     */
    protected $unifaunPcxpressUnifaunFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProductFactory
     */
    protected $unifaunPcxpressUnifaunTransportProductFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Shipping
     */
    protected $unifaunShippingHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\InsuranceFactory
     */
    protected $unifaunPcxpressUnifaunInsuranceFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Consignor
     */
    protected $unifaunConsignorHelper;

    /**
     * @var \Pcxpress\Unifaun\Helper\Consignee
     */
    protected $unifaunConsigneeHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory
     */
    protected $unifaunPcxpressUnifaunCommunicationFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PartFactory
     */
    protected $unifaunPcxpressUnifaunPartFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ReferenceFactory
     */
    protected $unifaunPcxpressUnifaunReferenceFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsItemFactory
     */
    protected $unifaunPcxpressUnifaunGoodsItemFactory;

    /**
     * @var \Magento\Sales\Model\Order\Shipment\TrackFactory
     */
    protected $salesOrderShipmentTrackFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\PickupLocationFactory
     */
    protected $unifaunPickupLocationFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory
     */
    protected $unifaunPcxpressUnifaunAddressFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\LabelFactory
     */
    protected $unifaunLabelFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\PackageConfigFactory
     */
    protected $unifaunPackageConfigFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CustomsClearanceFactory
     */
    protected $unifaunPcxpressUnifaunCustomsClearanceFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\Request\Http $request,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment\Data $unifaunPcxpressUnifaunConsignmentData,
        \Pcxpress\Unifaun\Model\ParcelFactory $unifaunParcelFactory,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory,
        \Pcxpress\Unifaun\Helper\Pacsoft\Data $unifaunPacsoftDataHelper,
        \Magento\Backend\Model\Session $backendSession,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentFactory $unifaunPcxpressUnifaunConsignmentFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory $unifaunPcxpressUnifaunFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProductFactory $unifaunPcxpressUnifaunTransportProductFactory,
        \Pcxpress\Unifaun\Helper\Shipping $unifaunShippingHelper,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\InsuranceFactory $unifaunPcxpressUnifaunInsuranceFactory,
        \Pcxpress\Unifaun\Helper\Consignor $unifaunConsignorHelper,
        \Pcxpress\Unifaun\Helper\Consignee $unifaunConsigneeHelper,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory $unifaunPcxpressUnifaunCommunicationFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PartFactory $unifaunPcxpressUnifaunPartFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ReferenceFactory $unifaunPcxpressUnifaunReferenceFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsItemFactory $unifaunPcxpressUnifaunGoodsItemFactory,
        \Magento\Sales\Model\Order\Shipment\TrackFactory $salesOrderShipmentTrackFactory,
        \Pcxpress\Unifaun\Model\PickupLocationFactory $unifaunPickupLocationFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory $unifaunPcxpressUnifaunAddressFactory,
        \Pcxpress\Unifaun\Model\LabelFactory $unifaunLabelFactory,
        \Pcxpress\Unifaun\Model\PackageConfigFactory $unifaunPackageConfigFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CustomsClearanceFactory $unifaunPcxpressUnifaunCustomsClearanceFactory
    )
    {
        $this->unifaunPcxpressUnifaunCustomsClearanceFactory = $unifaunPcxpressUnifaunCustomsClearanceFactory;
        $this->resourceConnection = $resourceConnection;
        $this->request = $request;
        $this->unifaunPcxpressUnifaunConsignmentData = $unifaunPcxpressUnifaunConsignmentData;
        $this->unifaunParcelFactory = $unifaunParcelFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
        $this->unifaunPacsoftDataHelper = $unifaunPacsoftDataHelper;
        $this->backendSession = $backendSession;
        $this->unifaunPcxpressUnifaunConsignmentFactory = $unifaunPcxpressUnifaunConsignmentFactory;
        $this->unifaunPcxpressUnifaunFactory = $unifaunPcxpressUnifaunFactory;
        $this->unifaunPcxpressUnifaunTransportProductFactory = $unifaunPcxpressUnifaunTransportProductFactory;
        $this->unifaunShippingHelper = $unifaunShippingHelper;
        $this->unifaunPcxpressUnifaunInsuranceFactory = $unifaunPcxpressUnifaunInsuranceFactory;
        $this->unifaunConsignorHelper = $unifaunConsignorHelper;
        $this->unifaunConsigneeHelper = $unifaunConsigneeHelper;
        $this->unifaunPcxpressUnifaunCommunicationFactory = $unifaunPcxpressUnifaunCommunicationFactory;
        $this->scopeConfig = $scopeConfig;
        $this->unifaunPcxpressUnifaunPartFactory = $unifaunPcxpressUnifaunPartFactory;
        $this->unifaunPcxpressUnifaunReferenceFactory = $unifaunPcxpressUnifaunReferenceFactory;
        $this->unifaunPcxpressUnifaunGoodsItemFactory = $unifaunPcxpressUnifaunGoodsItemFactory;
        $this->salesOrderShipmentTrackFactory = $salesOrderShipmentTrackFactory;
        $this->unifaunPickupLocationFactory = $unifaunPickupLocationFactory;
        $this->unifaunPcxpressUnifaunAddressFactory = $unifaunPcxpressUnifaunAddressFactory;
        $this->unifaunLabelFactory = $unifaunLabelFactory;
        $this->unifaunPackageConfigFactory = $unifaunPackageConfigFactory;

        $resource = $this->resourceConnection;

        $this->writeConnection = $resource->getConnection('core_write');

//        $this->shipment_track_table = $resource->getTableName('sales_flat_shipment_track');

    }





    protected function _logShipmentErrorsMessage($message, \Magento\Sales\Model\Order\Shipment $shipment = null)
    {

        if ($shipment instanceof \Magento\Sales\Model\Order\Shipment && $shipment->getIncrementId()) {
            $message = $shipment->getIncrementId() . ": " . $message;
        }
        $this->backendSession->addError($message);
    }






    protected function updateTrackingCode($tracking_id, $track_number)
    {
        if (!$tracking_id) {
            return;
        }

        // var_dump($tracking_id);
        // var_dump($track_number);


        // var_dump($this->shipment_track_table);die;

        $query = "UPDATE {$this->shipment_track_table} SET track_number = '{$track_number}' WHERE entity_id = '{$tracking_id}'";

        // var_dump($query);

        $this->writeConnection->query($query);

    }


    protected function _getPickupLocationPart($addressId)
    {
        $pickupLocation = $this->unifaunPickupLocationFactory->create()->load($addressId);

        if (!$pickupLocation) {
            throw new \Exception('Pickup Location not found');
        }

        $address = $this->unifaunPcxpressUnifaunAddressFactory->create();
        $address->setId("-");

        $address->setName($pickupLocation->getName());

        $address->setAddress($pickupLocation->getAddress());

        $address->setCity($pickupLocation->getCity());

        $address->setPostCode($pickupLocation->getPostcode());

        $address->setState($pickupLocation->getState());

        $address->setCountryCode($pickupLocation->getCountrycode());

        $communication = $this->unifaunPcxpressUnifaunCommunicationFactory->create();

        if ($pickupLocation->getContactPerson()) {
            $communication->setContactPerson($pickupLocation->getContactPerson());
        }

        if ($pickupLocation->getPhone()) {
            $communication->setPhone($pickupLocation->getPhone());
        }

        if ($pickupLocation->getMobile()) {
            $communication->setMobile($pickupLocation->getMobile());
        }

        if ($pickupLocation->getFax()) {
            $communication->setFax($pickupLocation->getFax());
        }

        if ($pickupLocation->getEmail()) {
            $communication->setEmail($pickupLocation->getEmail());
        }

        $part = $this->unifaunPcxpressUnifaunPartFactory->create();

        $part->setAddress($address);
        $part->setCommunication($communication);
        $part->setRole("pickupAddress");
        return $part;
    }

    protected function _getShipmentWeight(\Magento\Sales\Model\Order\Shipment $shipment)
    {
        $totalWeight = 0;
        $totalQty = 0;

        foreach ($shipment->getAllItems() as $item) {
            $weight = (float)$item->getWeight();

            $qty = $item->getOrderItem()->getQtyToShip() * 1; // Will not give the quantity set in invoice configuration
            $totalQty += $qty;

            $totalWeight += $weight * $qty;
        }

        return $totalWeight;
    }


    protected function _getPackagesByMethodAndAdvice($parcels)
    {

        $packagesByMethodAndAdvice = array();


        foreach ($parcels as $package) {
            $packageKey = $package['shippingMethod'] . "-" . $package['advice'];

            if (!array_key_exists($packageKey, $packagesByMethodAndAdvice)) {
                $packagesByMethodAndAdvice[$packageKey] = array(

                    'method' => $package['shippingMethod'],

                    'advice' => $package['advice'],

                    'packages' => array()

                );
            }

            $packagesByMethodAndAdvice[$packageKey]['packages'][] = $package;
        }

        return $packagesByMethodAndAdvice;

    }


    public function addMassActionInSalesOrders(\Magento\Framework\Event\Observer $observer)
    {

        $block = $observer->getEvent()->getBlock();


        if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction && $block->getRequest()->getControllerName() == 'sales_order') {
            if ($block->getParentBlock() instanceof \Magento\Backend\Block\Widget\Grid) {
                $block->addItem(
                    'unifaun_create_consignment', array(
                        'label' => __('Unifaun: Create consignments'),
                        'url' => 'javascript:unifaunModule.batchProcessOrders(' . $block->getJsObjectName() . ', "' . $block->getUrl('unifaun/adminhtml_unifaunConsignment/checkbatch') . '", "' . $block->getUrl('unifaun/adminhtml_unifaunConsignment/batch') . '")',
                    )
                );


                $block->addItem(
                    'unifaun_create_consignment_print', array(
                        'label' => __('Unifaun: Create consignments and labels'),
                        'url' => 'javascript:unifaunModule.batchProcessOrders(' . $block->getJsObjectName() . ', "' . $block->getUrl('unifaun/adminhtml_unifaunConsignment/batchCheck') . '", "' . $block->getUrl('unifaun/adminhtml_unifaunConsignment/createAndPrint') . '")',
                    )
                );
            }
        }


    }


    public function createConsignmentLabels(\Magento\Framework\Event\Observer $observer)
    {


        $shipment = $observer->getShipment();


        $request = $this->request;


        $order = $shipment->getOrder();


        if (!$order->getId()) {
            throw new \Exception('The dose not exists.');
        }


        $carrierCode = $order->getShippingCarrier()->getCarrierCode();


        if (!$carrierCode) {
            $carrierCode = $order->getShippingMethod();
        }


        if (strpos($carrierCode, \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE) !== false) {
            $methodId = null;


            if ($this->unifaunHelper->isTemplateChangeEnabled()) {
                $methodId = $request->getParam("unifaun_method_id");
            }


            if (!$methodId) {
                $method = explode("_", $order->getShippingMethod());


                if (!array_key_exists(1, $method)) {
                    throw new \Exception('Not a valid method');
                }


                $methodId = (int)$method[1];
            }


            $method = $this->unifaunShippingMethodFactory->create()->load($methodId);


            if ($method->isObjectNew()) {
                throw new \Exception('Shipping Method no longer exists.');
            }


            if ($method->getLabelOnly()) {
                $label = $this->unifaunLabelFactory->create()->load($shipment->getId(), 'shipment_id');


                if ($label->getId()) {
                    return true;
                }


                $labelModel = $this->unifaunLabelFactory->create();


                $labelModel->setStatus(0)
                    ->setShipmentId($shipment->getId())
                    ->setCreatedAt(time())
                    ->save();


                return true;
            }
        }


        return true;


    }


    public function setPackageConfiguration(\Magento\Framework\Event\Observer $observer)
    {


        $product = $observer->getProduct();


        $request = $observer->getRequest();


        $productData = $request->getPost('product');


        $packageConfigurations = array();


        if (array_key_exists('package_configuration', $productData) && is_array($productData['package_configuration'])) {
            $packageConfigurationData = $productData['package_configuration'];


            foreach ($packageConfigurationData as $packageValue) {
                if (is_array($packageValue)) {
                    $packageConfig = $this->unifaunPackageConfigFactory->create();


                    $pvWidth = isset($packageValue['width']) ? $packageValue['width'] : null;


                    $pvHeight = isset($packageValue['height']) ? $packageValue['height'] : null;


                    $pvDepth = isset($packageValue['depth']) ? $packageValue['depth'] : null;


                    $pvWeight = isset($packageValue['weight']) ? $packageValue['weight'] : null;


                    $pvGoodsType = isset($packageValue['goodsType']) ? $packageValue['goodsType'] : null;


                    $pvShippingMethod = isset($packageValue['shippingMethod']) ? $packageValue['shippingMethod'] : null;


                    $pvAdvice = isset($packageValue['advice']) ? $packageValue['advice'] : null;


                    $packageConfig->setWidth(floatval($pvWidth));


                    $packageConfig->setHeight(floatval($pvHeight));


                    $packageConfig->setDepth(floatval($pvDepth));


                    $packageConfig->setWeight(floatval($pvWeight));


                    $packageConfig->setGoodsType($pvGoodsType);


                    $packageConfig->setShippingMethod($pvShippingMethod);


                    $packageConfig->setAdvice($pvAdvice);


                    $packageConfigurations[] = $packageConfig;
                } elseif ($packageValue instanceof \Pcxpress\Unifaun\Model\PackageConfig) {
                    $packageConfigurations[] = $packageValue;
                }
            }
        }


        $product->setPackageConfiguration($packageConfigurations);


        return $this;


    }


    public function updateOrderShipment($order)
    {


        $parcels = $this->request->getParam('parcel');


        if (!count($parcels)) {
            return;
        }


        $method = explode("_", $order->getShippingMethod());


        $methodId = (int)$method[1];


        $method = $this->unifaunShippingMethodFactory->create()->load($methodId);


        $unifaunCode = \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE;


        $newMethodId = false;

        $newMethodDesc = '';

        $updated = false;


        if (count($parcels)) {
            foreach ($parcels as $parcel) {
                if ($parcel['shippingMethod'] && $parcel['shippingMethod'] != $method->getId()) {
                    $newMethodId = $unifaunCode . '_' . $parcel['shippingMethod'];

                    $newMethod = $this->unifaunShippingMethodFactory->create()->load($parcel['shippingMethod']);

                    $newMethodDesc = $newMethod->getData('title');
                }
            }
        }


        if ($newMethodId) {
            $order->setShippingMethod($newMethodId);

            $updated = true;
        }

        if ($newMethodDesc) {
            $order->setShippingDescription($newMethodDesc);

            $updated = true;
        }


        if ($updated) {
            $order->save();
        }
        return;
    }
}
