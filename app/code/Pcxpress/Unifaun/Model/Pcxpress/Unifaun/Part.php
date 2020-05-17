<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class Part extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{


    public function setRole($value)
    {
        $this->_properties['role'] = $value;
    }


    /**
     * Communication information
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication $value
     */
    public function setCommunication(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication $value)
    {
        $this->_properties['Communication'] = $value;
    }

        /**
     * Reference information
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Reference $value
     */
    public function setReference($value)
    {
        $this->_properties['Reference'] = $value;
    }


    /**
     * Name of account in Unifaun System
     * @param string $value
     */
    public function setAccount($value)
    {
        if ($value === null && array_key_exists("account", $this->_properties)) {
            unset($this->_properties['account']);
        } else {
            $this->_properties['account'] = $value;
        }
    }

   
    /**
     * Address information
     * @param string $value
     */
    public function setAddress(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Address $value)
    {
        $this->_properties['Address'] = $value;
    }

    /**
     * Get Communication
     * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication
     */
    public function getCommunication()
    {
        return (isset($this->_properties['Communication']))? $this->_properties['Communication'] : '';
    }


   /**
     * Get Reference
     * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Reference
     */
    public function getReference()
    {
        return (isset($this->_properties['Reference']))? $this->_properties['Reference'] : '';
    }


    /**
     * Get Role
     * @return string
     */
    public function getRole()
    {
        return (isset($this->_properties['role']))? $this->_properties['role'] : '';
    }


   /**
     * Get Account
     * @return string
     */
    public function getAccount()
    {
        return array_key_exists("account", $this->_properties) ? $this->_properties["account"] : null;
    }

    /**
     * Get Address information
     * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Address
     */
    public function getAddress()
    {
        return (isset($this->_properties['Address']))? $this->_properties['Address'] : '';
    }


}