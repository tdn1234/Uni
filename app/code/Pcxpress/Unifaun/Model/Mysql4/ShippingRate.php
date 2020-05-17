<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Mysql4;

class ShippingRate extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        parent::__construct(
            $context,
            $connectionName
        );
    }


    public function _construct()
    {
        $this->_init('unifaun/shippingRate', 'shippingrate_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $countries = $object->getCountries();

        if ($countries) {
            $object->setCountries(json_encode(array_values($countries)));
        } else {
            $object->setCountries(null);
        }

        return parent::_beforeSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $result = parent::_afterLoad($object);

        $countries = $object->getCountries();
        if ($countries) {
            $countries = json_decode($countries, true);
        }

        $object->setCountries(is_array($countries) ? $countries : array());

        return $result;
    }


}