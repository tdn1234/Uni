<?php
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;


/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
class Pickup extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
    protected $_namespace = "http://www.spedpoint.com/consignment/types/v1_0";


    /**
     * Date time string
     * @param string|null $value
     */
    public function setDate($value)
    {
        $this->_properties['date'] = $value;
    }

    /**
     * Date time string
     * @param string|null $value
     */
    public function setEarliest($value)
    {
        $this->_properties['earliest'] = $value;
    }


  
    /**
     * Date time string
     * @param string|null $value
     */
    public function setLatest($value)
    {
        $this->_properties['latest'] = $value;
    }

    /**
     * Array of 1-3 strings (not required)
     * @param array|null $value
     */
    public function setInstruction($value)
    {
        $this->_properties['instruction'] = $value;
    }

    /**
     * Array of 1-3 strings (not required)
     * @param array|null $value
     */
    public function setLocation($value)
    {
        $this->_properties['location'] = $value;
    }

    /**
     * Date time string
     * @return string|null
     */
    public function getDate()
    {
        return (isset($this->_properties['date']))? $this->_properties['date'] : '';
    }



    public function getLatest()
    {
        return (isset($this->_properties['latest']))? $this->_properties['latest'] : '';
    }


    public function getInstruction()
    {
        return (isset($this->_properties['instruction']))? $this->_properties['instruction'] : '';
    }


    public function getLocation()
    {
        return (isset($this->_properties['location']))? $this->_properties['location'] : '';
    }

      /**
     * Date time string
     * @return string|null
     */
    public function getEarliest()
    {
        return (isset($this->_properties['earliest']))? $this->_properties['earliest'] : '';
    }

}