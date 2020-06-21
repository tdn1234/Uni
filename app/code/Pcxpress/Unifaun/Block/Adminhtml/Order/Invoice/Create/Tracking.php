<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\Order\Invoice\Create;

class Tracking extends \Magento\Shipping\Block\Adminhtml\Order\Tracking
{

    /**
     * @var \Pcxpress\Unifaun\Model\ParcelFactory
     */
    protected $unifaunParcelFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $catalogProductFactory;

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

    public function __construct(
        \Pcxpress\Unifaun\Model\ParcelFactory $unifaunParcelFactory,
        \Magento\Catalog\Model\ProductFactory $catalogProductFactory,
        \Pcxpress\Unifaun\Model\SourceModel\AdviceTypesFactory $unifaunSourceModelAdviceTypesFactory,
        \Pcxpress\Unifaun\Model\ShippingmethodFactory $unifaunShippingMethodFactory,
        \Pcxpress\Unifaun\Model\Mysql4\PickupLocation\CollectionFactory $unifaunMysql4PickupLocationCollectionFactory,
        \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory
    ) {
        $this->unifaunParcelFactory = $unifaunParcelFactory;
        $this->catalogProductFactory = $catalogProductFactory;
        $this->unifaunSourceModelAdviceTypesFactory = $unifaunSourceModelAdviceTypesFactory;
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
        $this->unifaunMysql4PickupLocationCollectionFactory = $unifaunMysql4PickupLocationCollectionFactory;
        $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
    }
    public function getOrder()
    {
        return $this->getInvoice()->getOrder();
    }

    /**
     * @return array
     */
    public function getParcels()
    {
        return $this->unifaunParcelFactory->create()->setEntity($this->getInvoice())->getParcels();
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        
        $order = $this->getOrder();

        $shippingCarrier = $order->getShippingCarrier();
        $carrierCode = $shippingCarrier->getCarrierCode();
        if(!$carrierCode){
            $carrierCode = $order->getShippingMethod();
        }

        
        if (strpos($carrierCode, \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE) === false) {
            
            return parent::_toHtml();
        }

        $totalWeight = $this->getTotalWeight();

        $this->setAttribute("weight", $totalWeight);
        $this->setTemplate("unifaun/shipment/tracking.phtml");

        return parent::_toHtml();
    }

    private function getTotalWeight(){
        $totalWeight = 0;
        $totalQty = 0;
        $invoice = $this->getInvoice();
        foreach ($invoice->getAllItems() as $invoiceItem) {            
            $product = $this->catalogProductFactory->create()->load($invoiceItem->getProductId());
            if ($this->isSimpleProduct($product)) {
                continue;
            }
            $productWeight = (float) $product->getWeight();
            $productQty = $invoiceItem->getQty() * 1;
            $totalQty += $productQty;
            $totalWeight += $productWeight * $productQty;
        }
        return $totalWeight;
    }

    private function isSimpleProduct($product)
    {
        return ($product->isObjectNew() || $product->isConfigurable());
    }
		
	public function getAdviceTypes()
    {
      return $this->unifaunSourceModelAdviceTypesFactory->create()->toOptionArray();  
    }
		
		public function getShippingMethod()
    {
        if (!$this->_shippingMethod) {        
            $firstShippingMethodId = $this->getFirstShippingMethodIdFromOrder();
            $this->_shippingMethod = $this->unifaunShippingMethodFactory->create()->load($firstShippingMethodId);
        }

        return $this->_shippingMethod;
    }

    private function getFirstShippingMethodIdFromOrder(){
        $order = $this->getOrder();
        $orderShippingMethod = $order->getShippingMethod();
        $shippingMethodIds = explode("_", $orderShippingMethod);
        return $shippingMethodIds[1];
    }
		
		public function getShippingAddress()
    {
        $order = $this->getOrder();

        return $order->getShippingAddress();
    }
		
		public function getPickupLocations()
    {
        /** @var \Pcxpress\Unifaun\Model\Mysql4\PickupAddress\Collection $collection */
        $collection = $this->unifaunMysql4PickupLocationCollectionFactory->create();
        $collection->setOrder('city', 'ASC');

        $addresses = array();
        foreach ($collection as $address) {
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
            $weekDay = $this->__(date('l', strtotime("+$i day")));
            $today = ($i == 0) ? '(' . $this->__('Today') . ')' : '';
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
		
		public function getShippingMethodsWithTemplate()
    {
        $collection = $this->unifaunMysql4ShippingMethodCollectionFactory->create();
        $collection->addFieldToFilter("no_booking", 0);
        $collection->addFieldToFilter("template_name", array("neq" => ""));
        $collection->addFieldToFilter("label_only", 0);

        return $collection;
    }
		
		private function getShippingMethodSelectOptions()
    {
        /** @var \Pcxpress\Unifaun\Model\ShippingMethod $method */

        $methodGroups = array();
        $selectedId = $this->getShippingMethod()->getId();

        $group = array('groupName' => $this->__("Pcxpress Unifaun"), 'methods' => array());
        foreach ($this->getShippingMethodsWithTemplate() as $method) {
            $group['methods'][] = array(
                'id'       => $method->getShippingmethodId(),
                'title'    => $method->getTitle(),
                'selected' => $selectedId == $method->getShippingmethodId()
            );;
        }
        $methodGroups[] = $group;

        $group = array('groupName' => $this->__("Label Queue"), 'methods' => array());
        foreach ($this->getShippingMethodsWithLabel() as $method) {
            $group['methods'][] = array(
                'id'       => $method->getShippingmethodId(),
                'title'    => $method->getTitle(),
                'selected' => $selectedId == $method->getShippingmethodId()
            );;
        }
        $methodGroups[] = $group;

        $group = array('groupName' => $this->__("No booking"), 'methods' => array());
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
		
		public function getShippingMethodsWithLabel()
    {
        $collection = $this->unifaunMysql4ShippingMethodCollectionFactory->create();
        /* @var $collection Pcxpress_Unifaun_Model_Mysql4_ShippingMethod_Collection */
        $collection->addFieldToFilter("label_only", 1);

        return $collection;
    }
		
		public function getShippingMethodsWithNoBooking()
    {
        $collection = $this->unifaunMysql4ShippingMethodCollectionFactory->create();
        /* @var $collection Pcxpress_Unifaun_Model_Mysql4_ShippingMethod_Collection */
        $collection->addFieldToFilter("label_only", 0);
        $collection->addFieldToFilter("no_booking", 1);

        return $collection;
    }

}