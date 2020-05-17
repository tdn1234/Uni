<?php

/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

/**
 * Used by Pcxpress_Unifaun_Block_Adminhtml_Order_Invoice_Create_Tracking and
 * Pcxpress_Unifaun_Block_Adminhtml_Order_Shipment_Create_Tracking.
 */
trait Pcxpress_Unifaun_Block_Adminhtml_Order_unifaunTrackingTrait
{
    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethod
     */
    protected $_shippingMethod;

    /**
     * @return \Magento\Sales\Model\Order
     */
    abstract protected function getOrder();

    /**
     * @return array
     */
    abstract protected function getNumberOfBoxes();

    /**
     * @return array|string[]
     */
    public function getAdviceTypes()
    {
        return array(
            'none'   => Mage::helper('unifaun')->__("None selected"),
            'phone'  => Mage::helper('unifaun')->__("Phone"),
            'postal' => Mage::helper('unifaun')->__("Postal"),
            'mobile' => Mage::helper('unifaun')->__("Cellphone"),
            'fax'    => Mage::helper('unifaun')->__("Fax"),
            'email'  => Mage::helper('unifaun')->__("E-mail"),
        );
    }

    /**
     * Get the selected shipping method for this order
     * @return \Pcxpress\Unifaun\Model\ShippingMethod
     */
    public function getShippingMethod()
    {
        if (!$this->_shippingMethod) {
            $order = $this->getOrder();
            $shippingMethodParts = explode("_", $order->getShippingMethod());
            $shippingMethodId = $shippingMethodParts[1];
            $this->_shippingMethod = Mage::getModel('unifaun/shippingMethod')->load($shippingMethodId);
        }

        return $this->_shippingMethod;
    }

    /**
     * Get all shipping methods
     *
     * @return \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\Collection|\Pcxpress\Unifaun\Model\ShippingMethod[]
     */
    public function getShippingMethods()
    {
        return Mage::getModel('unifaun/shippingMethod')->getCollection();
    }

    /**
     * Get all shipping methods with template that we can ship to.
     * @return \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\Collection|\Pcxpress\Unifaun\Model\ShippingMethod[]
     */
    public function getShippingMethodsWithTemplate()
    {
        $collection = Mage::getModel('unifaun/shippingMethod')->getCollection();
        /* @var $collection Pcxpress_Unifaun_Model_Mysql4_ShippingMethod_Collection */
        $collection->addFieldToFilter("no_booking", 0);
        $collection->addFieldToFilter("template_name", array("neq" => ""));
        $collection->addFieldToFilter("only_label", 0);

        return $collection;
    }

    /**
     * Get all shipping methods with label that we can ship to.
     * @return \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\Collection|\Pcxpress\Unifaun\Model\ShippingMethod[]
     */
    public function getShippingMethodsWithLabel()
    {
        $collection = Mage::getModel('unifaun/shippingMethod')->getCollection();
        /* @var $collection Pcxpress_Unifaun_Model_Mysql4_ShippingMethod_Collection */
        $collection->addFieldToFilter("only_label", 1);

        return $collection;
    }

    /**
     * Get all shipping methods with template that we can ship to
     * @return array
     */
    public function getShippingMethodsWithNoBooking()
    {
        $collection = Mage::getModel('unifaun/shippingMethod')->getCollection();
        /* @var $collection Pcxpress_Unifaun_Model_Mysql4_ShippingMethod_Collection */
        $collection->addFieldToFilter("only_label", 0);
        $collection->addFieldToFilter("no_booking", 1);

        return $collection;
    }

    /**
     * Retrieve order shipping address.
     *
     * @return \Magento\Sales\Model\Order\Address
     */
    public function getShippingAddress()
    {
        $order = $this->getOrder();

        return $order->getShippingAddress();
    }

    /**
     * @param $value
     * @return string
     */
    public function getJson($value)
    {
        return json_encode($value, JSON_HEX_QUOT);
    }

    /**
     * Generate a list of possible pick up dates.
     *
     * @return array
     */
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

    /**
     * @return array
     */
    public function getPickupAddresses()
    {
        /** @var \Pcxpress\Unifaun\Model\Mysql4\PickupAddress\Collection $collection */
        $collection = Mage::getModel('unifaun/pickupAddress')->getCollection();
        $collection->setOrder('address_city', 'ASC');

        $addresses = array();
        foreach ($collection as $address) {
            /** @var \Pcxpress\Unifaun\Model\PickupAddress $address */
            $addresses[] = array(
                'key'   => $address->getId(),
                'value' => $address->getAddressName() . ' ' . $address->getAddressAddress1(),
            );
        }

        return $addresses;
    }

    /**
     * Used to pass data to the javascript in the template.
     *
     * @return \stdClass
     */
    public function getConfig()
    {
        $data = new \stdClass();
        $data->lastBookingData = $this->getLastBookingData();
        $data->parcels = $this->getParcelsData();
        $data->shippingMethods = $this->getShippingMethodSelectOptions();

        return $data;
    }

    /**
     * Get the last possible booking time for every method.
     *
     * @return array
     */
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

    /**
     * Get the parcels in a javascript friendly way.
     *
     * @return array
     */
    protected function getParcelsData()
    {
        $parcels = array();
        foreach ($this->getNumberOfBoxes() as $box) {
            $parcels[] = array(
                'width'          => $box['width'],
                'height'         => $box['height'],
                'depth'          => $box['depth'],
                'weight'         => $box['weight'],
                'note'           => $box['note'],
                'goodsType'      => $box['goodsType'],
                'shippingMethod' => $box['shippingMethod'],
                'advice'         => $box['advice'],
            );
        }

        return $parcels;
    }

    /**
     * @return array
     */
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
}
