<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class Address extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
	protected $_namespace = "http://www.spedpoint.com/consignment/types/v1_0";
	
	
	
	/**
	 * Id of address
	 * @param string $value
	 */
	public function setId($value)
	{
		$this->_properties['id'] = $value;
	}
	

	
	/**
	 * Name
	 * @param string $value
	 */
	public function setName($value)
	{
		$this->_properties['name'] = $value;
	}

	
	public function setAddress($address)
	{
		if(is_array($address)){
			$this->_properties['address'] = $address;
		}
		else{
			$this->_properties['address'] = array($address);
		}
	}

	
	/**
	 * City
	 * @param string $value
	 */
	public function setCity($value)
	{
			$this->_properties['city'] = $value;
	}
	
	
	/**
	 * Post code according to rules for country
	 * @param string $value
	 */
	public function setPostCode($value)
	{
		$this->_properties['postcode'] = $value;
	}

	
	/**
	 * Mode of transport
	 * @param string $value
	 */
	public function setState($value)
	{
		$this->_properties['state'] = $value;
	}

	
	/**
	 * Country Code
	 * @param string $value
	 */
	public function setCountryCode($value)
	{
		$this->_properties['countrycode'] = $value;
	}
	

	
	/**
	 * Position X Coordinate
	 * @param float $value
	 */
	public function setPosX($value)
	{
		$this->_properties['posX'] = $value;
	}

	
	/**
	 * Position Y Coordinate
	 * @param float $value
	 */
	public function setPosY($value)
	{
		$this->_properties['posY'] = $value;
	}
	/**
	 * name of position system used
	 * @param string $value
	 */
	public function setPosSystem($value)
	{
		$this->_properties['posSystem'] = $value;
	}
	
	protected function _prepareAddress($value)
	{
		if (is_array($value) && count($value) == 1) {
				return array_pop($value);
		} elseif (!is_array($value)) {
				return null;
		}

		$value = "{{{multiple-element:" . join("||", $value) . "}}}";
		return $value;
	}

	public function getPosSystem()
	{
		return $this->_properties['posSystem'];
	}

		public function getPosY()
	{
		return $this->_properties['posY'];
	}

	public function getPosX()
	{
		return $this->_properties['posX'];
	}

		public function getCountryCode()
	{
			return $this->_properties['countrycode'];
	}

	public function getPostCode()
	{
		return $this->_properties['postcode'];
	}

	public function getState()
	{
		return $this->_properties['state'];
	}

	
	public function getCity()
	{
		return $this->_properties['city'];
	}

	public function getAddress()
	{
		return $this->_properties['address'];
	}


	public function getName()
	{
		return $this->_properties['name'];
	}


	public function getId()
	{
		return $this->_properties['id'];
	}

}