<?php
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;


/**

 * @category   PC  xpressPCXpress AB

 * @package    Pcxpress_Unifaun

 * @copyright  Copyright (c) 2017 PCXpress AB

 * @author     PCXpress AB Developer <info@pcxpress.se>

 * @license    http://pcxpress.se/magento/license.txt

 */

class TransportProduct extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract

{

    protected $_namespace = "http://www.spedpoint.com/consignment/types/v1_0";

     protected $_properties = array(
            'mode' => null,
            'code' => null,
            'advice' => null,
            'co' => null,
            'Pickup' => null,
            'PaymentInstruction' => null,
            'CustomsClearance' => null,
            'Insurance' => null
    );
   

    const PAYMENTINSTRUCTION_PREPAID = "P";



    /**

     * Mode of transport

     * @param string $value

     */

    public function setMode($value)

    {

        $this->_properties['mode'] = $value;

    }



    /**

     * Advice

     * @param bool $value

     */

    public function setAdvice($value)

    {

        $this->_properties['advice'] = ($value == true) ? "true" : "false";

    }



        /**

     * Advice

     * @param bool $value

     */

    public function setAdviceType($value)

    {

        $this->_properties['advice_type'] = $value;

    }





        public function setCustomsClearance(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CustomsClearance $customsClearance)

    {

        $this->_properties['CustomsClearance'] = $customsClearance;

    }



    /**

     * Product code

     * @param string $value

     */

    public function setCode($value)

    {

        $this->_properties['code'] = $value;

    }



    protected function _preparePaymentInstruction($value) {

        $obj = new \stdClass();

        $obj->value = $value;

        return $obj;

    }



    public function setPaymentInstruction($value)

    {

        $this->_properties['PaymentInstruction'] = $value;

    }


    public function unsetPaymentInstruction()

    {

        unset($this->_properties['PaymentInstruction']);

    }


    /**

     * Set Cash On Delivery

     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod $value

     */

    public function setCod(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod $value)

    {

        $this->_properties['Cod'] = $value;

    }



    /**

     * Set Pickup

     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Pickup $value

     */

    public function setPickup(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Pickup $value)

    {

        $this->_properties['Pickup'] = $value;

    }



      public function getAdvice()

    {

        return ($this->_properties['advice'] == "true" ? true : false);

    }      



    public function getAdviceType()

    {

        return (isset($this->_properties['advice_type']))? $this->_properties['advice_type'] : '';

    }





    /**

     * @return mixed

     */

    public function getCustomsClearance()

    {

        return (isset($this->_properties['CustomsClearance']))? $this->_properties['CustomsClearance'] : '';

    }



        public function getMode()

    {

        return (isset($this->_properties['mode']))? $this->_properties['mode'] : '';

    }



        public function getCode()

    {

        return (isset($this->_properties['code']))? $this->_properties['code'] : '';

    }



      public function getPaymentInstruction()

    {

        return $this->_properties['PaymentInstruction'];

    }



        public function getCod()

    {

        return (isset($this->_properties['Cod']))? $this->_properties['Cod'] : '';

    }



        public function getPickup()

    {

        return (isset($this->_properties['Pickup']))? $this->_properties['Pickup'] : '';

    }

         public function setInsurance(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Insurance $item)
    {
        $this->_properties['Insurance'] = $item;
    }

     public function getInsurance()
    {
        return (isset($this->_properties['Insurance']))? $this->_properties['Insurance'] : '';
    }

}