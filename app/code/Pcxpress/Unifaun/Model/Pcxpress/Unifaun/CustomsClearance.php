<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class CustomsClearance extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
    protected $_namespace = "http://www.spedpoint.com/consignment/types/v1_0";


    /**
     * @param $value
     */
    public function setGoodsValue($value)
    {
        $this->_properties['goodsValue'] = $value;
    }

        public function setGoodsValueCurrency($value)
    {
        $this->_properties['goodsValueCurrency'] = $value;
    }

    /**
     * @return mixed
     */
    public function getGoodsValueCurrency()
    {
        return (isset($this->_properties['goodsValueCurrency']))? $this->_properties['goodsValueCurrency'] : '';
    }


     public function getGoodsValue()
    {
        return (isset($this->_properties['goodsValue']))? $this->_properties['goodsValue'] : '';
    }
}