<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class Insurance extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
	protected $_namespace = "http://www.spedpoint.com/consignment/types/v1_0";
	
	protected $_properties = array(
		'amount' => null,
		'currency' => 'SEK'
	);

	
	/**
    * 
     * @param float $value
     */
    public function setAmount($value)
    {
        $this->_properties['amount'] = $value;
    }

   /**
     * Get Insurance amount
     * @return amount
     */
    public function getAmount()
    {
        return (isset($this->_properties['amount']))? $this->_properties['amount'] : '';
    }

    /**
    * 
     * @param string $value
     */
    public function setCurrency($value)
    {
        $this->_properties['currency'] = $value;
    }

   /**
     * Get Insurance currency
     * @return currency
     */
    public function getCurrency()
    {
        return (isset($this->_properties['currency']))? $this->_properties['currency'] : '';
    }

}