<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class Error extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{

    protected $_trace;

       public function setTrace($value)
    {
        $this->_trace = $value;
    }

    /**
     * Code
     * @param string $value
     */
    public function setCode($value)
    {
        $this->_properties['code'] = $value;
    }

  

    /**
     * Description
     * @param string $value
     */
    public function setDescription($value)
    {
        $this->_properties['description'] = $value;
    }



    /**
     * Level
     * @param int $value
     */
    public function setLevel($value)
    {
        $this->_properties['level'] = $value;
    }

    /**
     * Get trace as string
     * @return string
     */
    public function getTrace()
    {
        return $this->_trace;
    }

        public function getCode()
    {
        return (isset($this->_properties['code']))? $this->_properties['code'] : '';
    } 

     public function getLevel()
    {
        return (isset($this->_properties['level']))? $this->_properties['level'] : '';
    }

      public function getDescription()
    {
        return (isset($this->_properties['description']))? $this->_properties['description'] : '';
    }

}