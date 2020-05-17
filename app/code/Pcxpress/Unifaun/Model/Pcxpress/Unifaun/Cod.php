<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class Cod extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
    protected $_namespace = "http://www.spedpoint.com/consignment/types/v1_0";


    const PAYMENTMETHOD_CASH = "CASH";
    const PAYMENTMETHOD_CHEQUE = "CHEQUE";
    const PAYMENTMETHOD_POST = "POST";
    const PAYMENTMETHOD_BANK = "BANK";

    public function setReference($value)
    {
        $this->_properties['reference'] = $value;
    }
    /**
     * Amount
     * @param float $value
     */
    public function setAmount($value)
    {
        $this->_properties['amount'] = $value;
    }

    /**
     * Currency
     * @param float $value
     */
    public function setCurrency($value)
    {
        $this->_properties['currency'] = $value;
    }

    /**
     * Currency Code
     * @param string $value
     */
    public function setCurrencyCode($value)
    {
        $this->_properties['currency'] = $value;
    }

    /**
     * Payment Method
     * @param string $value
     */
    public function setPaymentMethod($value)
    {
        $this->_properties['paymentMethod'] = $value;
    }

    /**
     * Account No
     * @param string $value
     */
    public function setAccountNo($value)
    {
        $this->_properties['accountNo'] = $value;
    }

   /**
     * Get Reference
     * @return string
     */
    public function getReference()
    {
        return (isset($this->_properties['reference']))? $this->_properties['reference'] : '';
    }

     public function getAccountNo()
    {
        return (isset($this->_properties['accountNo']))? $this->_properties['accountNo'] : '';
    }

     public function getPaymentMethod()
    {
        return (isset($this->_properties['paymentMethod']))? $this->_properties['paymentMethod'] : '';
    }

     public function getCurrency()
    {
        return (isset($this->_properties['currency']))? $this->_properties['currency'] : '';
    }

        public function getAmount()
    {
        return (isset($this->_properties['amount']))? $this->_properties['amount'] : '';
    }
}