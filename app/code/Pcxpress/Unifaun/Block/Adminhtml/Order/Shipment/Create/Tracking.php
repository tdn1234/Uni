<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\Order\Shipment\Create;

use Pcxpress\Unifaun\Model\ParcelFactory;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;
use Pcxpress\Unifaun\Model\SourceModel\AdviceTypesFactory;
use Pcxpress\Unifaun\Model\PickupLocationFactory;


class Tracking extends \Magento\Shipping\Block\Adminhtml\Order\Tracking
{

    /**
     * @var \Pcxpress\Unifaun\Model\ParcelFactory
     */
    protected $unifaunParcelFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\SourceModel\AdviceTypesFactory
     */
    protected $unifaunSourceModelAdviceTypesFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\PickupLocation\CollectionFactory
     */
    protected $unifaunMysql4PickupLocationCollectionFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory
     */
    protected $unifaunMysql4ShippingMethodCollectionFactory;

    // public function __construct(
    //     \Pcxpress\Unifaun\Model\ParcelFactory $unifaunParcelFactory,
    //     \Pcxpress\Unifaun\Model\SourceModel\AdviceTypesFactory $unifaunSourceModelAdviceTypesFactory,
    //     \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory,
    //     \Pcxpress\Unifaun\Model\Mysql4\PickupLocation\CollectionFactory $unifaunMysql4PickupLocationCollectionFactory,
    //     \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory
    // ) {
    //     $this->unifaunParcelFactory = $unifaunParcelFactory;
    //     $this->unifaunSourceModelAdviceTypesFactory = $unifaunSourceModelAdviceTypesFactory;
    //     $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
    //     $this->unifaunMysql4PickupLocationCollectionFactory = $unifaunMysql4PickupLocationCollectionFactory;
    //     $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
    // }

    protected $unifaunHelper;

    protected $_shippingMethod;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Framework\Registry $registry,
        array $data = [],
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        ShippingmethodFactory $shippingmethodFactory,
        ParcelFactory $unifaunParcelFactory,
        AdviceTypesFactory $adviceTypesFactory,
        PickupLocationFactory $unifaunMysql4PickupLocationCollectionFactory
    ){

        parent::__construct($context, $shippingConfig, $registry, $data);
        // $this->customerSession = $customerSession;
        // $this->_objectManager = $objectManager;
        $this->unifaunShippingMethodFactory = $shippingmethodFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunParcelFactory = $unifaunParcelFactory;
        $this->unifaunSourceModelAdviceTypesFactory = $adviceTypesFactory;
        $this->unifaunMysql4PickupLocationCollectionFactory = $unifaunMysql4PickupLocationCollectionFactory;
    }


    /**  **/
    public function getUnifaunHelper()
    {
        return $this->unifaunHelper;
    }


    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getShipment()->getOrder();
    }

    /**
     * @return array
     */
    public function getParcels()
    {
        return $this->unifaunParcelFactory->create()->setEntity($this->getShipment())->getParcels();
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        $currentShipment = $this->getShipment();

        $order = $this->getShipment()->getOrder();

        // $carrier = $order->getShippingCarrier();
        $carrier = $order->getShippingMethod();

        if (strpos($carrier, \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE) !== false ) {
             // We shall only do something with our own carrier, so return default view for everything else.
            return parent::_toHtml();
        }
        // var_dump(strpbrk('sss', 'gg'));
        // var_dump(\Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE);
        // var_dump($carrier);
        // var_dump(strpos($carrier, \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE));die;
        // var_dump(get_class_methods($order));die;
        // var_dump(get_class($carrier));die;

        // if ($carrier->getCarrierCode() != \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE) {
        //     // We shall only do something with our own carrier, so return default view for everything else.
        //     return parent::_toHtml();
        // }


        $totalWeight = 0;

        foreach ($currentShipment->getAllItems() as $item) {
            $weight = (float) $item->getWeight();
            $qty = $item->getOrderItem()->getQtyToShip() * 1;
            $totalWeight += $weight * $qty;
        }

        $this->setAttribute("weight", $totalWeight);

        $this->setTemplate("unifaun/shipment/tracking12.phtml");

        return parent::_toHtml();
    }

    public function getAdviceTypes()
    {
      return $this->unifaunSourceModelAdviceTypesFactory->create()->toOptionArray();  
  }

  public function getShippingMethod()
  {
    if (!$this->_shippingMethod) {
        $order = $this->getOrder();
        $shippingMethodParts = explode("_", $order->getShippingMethod());
        $shippingMethodId = $shippingMethodParts[1];

        $this->_shippingMethod = $this->unifaunShippingMethodFactory->create()->load($shippingMethodId);
    }

    return $this->_shippingMethod;
}

public function getShippingAddress()
{
    $order = $this->getOrder();

    return $order->getShippingAddress();
}
public function getPickupLocations()
{
    /** @var \Pcxpress\Unifaun\Model\Mysql4\PickupAddress\Collection $collection */
    $collection = $this->unifaunMysql4PickupLocationCollectionFactory->create()->getCollection();
    $collection->setOrder('city', 'ASC');


    $addresses = array();
    foreach ($collection as $address) {
        var_dump($address);die;
        /** @var \Pcxpress\Unifaun\Model\PickupAddress $address */
        $addresses[] = array(
            'key'   => $address->getId(),
            'value' => $address->getName() . ' ' . $address->getAddress(),
        );
    }
    return $addresses;
}
public function getPickUpDates()
{
    $dates = array();
    for ($i = 0; $i < 5; $i++) {
        $key = date('Y-m-d', strtotime("+$i day"));
        $weekDay = __(date('l', strtotime("+$i day")));
        $today = ($i == 0) ? '(' . __('Today') . ')' : '';
        $dates[] = array(
            'key'   => $key,
            'value' => "$key $weekDay $today",
        );
    }

    return $dates;
}

public function getConfig()
{
    $data = new \stdClass();
    $data->lastBookingData = $this->getLastBookingData();
    $data->parcels = $this->getParcelsData();
    $data->shippingMethods = $this->getShippingMethodSelectOptions();

    return $data;
}

protected function getLastBookingData()
{
    $lastBookingData = array();
    /** @var \Pcxpress\Unifaun\Model\ShippingMethod $method */
    foreach ($this->getShippingMethodsWithTemplate() as $method) {
        $lastBookingTime = explode(":", $method->getLastBookingTime());

        if (count($lastBookingTime) >= 2) {
            $lastBookingHour = intval(ltrim($lastBookingTime[0], "0"));
            $lastBookingMinute = intval(ltrim($lastBookingTime[1], "0"));
        } else {
            $lastBookingHour = 23;
            $lastBookingMinute = 59;
        }

        $lastBookingData[$method->getShippingmethodId()] = array(
            'hour'   => (int) $lastBookingHour,
            'minute' => (int) $lastBookingMinute,
        );
    }

    return $lastBookingData;
}

protected function getParcelsData()
{
    $parcels = array();

    foreach ($this->getParcels() as $parcel) {
        $parcels[] = array(
            'width'          => $parcel['width'],
            'height'         => $parcel['height'],
            'depth'          => $parcel['depth'],
            'weight'         => $parcel['weight'],
            'note'           => $parcel['note'],
            'goodsType'      => $parcel['goodsType'],
            'shippingMethod' => $parcel['shippingMethod'],
            'advice'         => $parcel['advice'],
        );
    }

    return $parcels;
}

private function getShippingMethodSelectOptions()
{
    /** @var \Pcxpress\Unifaun\Model\ShippingMethod $method */

    $methodGroups = array();
    $selectedId = $this->getShippingMethod()->getId();

    $group = array('groupName' => __("Pcxpress Unifaun"), 'methods' => array());
    foreach ($this->getShippingMethodsWithTemplate() as $method) {
        $group['methods'][] = array(
            'id'       => $method->getShippingmethodId(),
            'title'    => $method->getTitle(),
            'selected' => $selectedId == $method->getShippingmethodId()
        );;
    }
    $methodGroups[] = $group;

    $group = array('groupName' => __("Label Queue"), 'methods' => array());
    foreach ($this->getShippingMethodsWithLabel() as $method) {
        $group['methods'][] = array(
            'id'       => $method->getShippingmethodId(),
            'title'    => $method->getTitle(),
            'selected' => $selectedId == $method->getShippingmethodId()
        );;
    }
    $methodGroups[] = $group;

    $group = array('groupName' => __("No booking"), 'methods' => array());
    foreach ($this->getShippingMethodsWithNoBooking() as $method) {
        $group['methods'][] = array(
            'id'       => $method->getShippingmethodId(),
            'title'    => $method->getTitle(),
            'selected' => $selectedId == $method->getShippingmethodId()
        );;
    }
    $methodGroups[] = $group;

    return $methodGroups;
}

public function getShippingMethodsWithTemplate()
{
    $collection = $this->unifaunShippingMethodFactory->create()->getCollection();
    $collection->addFieldToFilter("no_booking", 0);
    $collection->addFieldToFilter("template_name", array("neq" => ""));
    $collection->addFieldToFilter("label_only", 0);

    return $collection;
}

public function getShippingMethodsWithLabel()
{
    $collection = $this->unifaunShippingMethodFactory->create()->getCollection();
    /* @var $collection Pcxpress_Unifaun_Model_Mysql4_ShippingMethod_Collection */
    $collection->addFieldToFilter("label_only", 1);

    return $collection;
}

public function getShippingMethodsWithNoBooking()
{
    $collection = $this->unifaunShippingMethodFactory->create()->getCollection();
    /* @var $collection Pcxpress_Unifaun_Model_Mysql4_ShippingMethod_Collection */
    $collection->addFieldToFilter("label_only", 0);
    $collection->addFieldToFilter("no_booking", 1);

    return $collection;
}
}