<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class StatusResult extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{

    /**
     * Set Errors
     * @param array $value
     */
    public function setErrors($value)
    {
        $this->_properties['errors'] = $value;
    }

    /**
     * Set status
     * @param array $value
     */
    public function setStatus($value)
    {
        $this->_properties['status'] = $value;
    }

    /**
     * Set status code
     * @param int $value
     */
    public function setStatusCode($value)
    {
        $this->_properties['statusCode'] = $value;
    }

        public function getStatusCode()
    {
        return (isset($this->_properties['statusCode']))? $this->_properties['statusCode'] : '';
    }

        public function getErrors()
    {
        return (isset($this->_properties['errors']))? $this->_properties['errors'] : '';
    }

        public function getStatus()
    {
        return (isset($this->_properties['status']))? $this->_properties['status'] : '';
    }

}