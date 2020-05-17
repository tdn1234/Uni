<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class Communication extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{

    protected $_namespaceForProperties = array("contactPerson" => "http://www.spedpoint.com/consignment/types/v1_0",
                                               "phone" => "http://www.spedpoint.com/consignment/types/v1_0");

    protected $_notify = null;
    const NOADVICE = "none";
    const POSTAL = "postal";
    const MOBILE = "mobile";
    const FAX = "fax";
    const EMAIL = "email";
    const PHONE = "phone"; 
    

    public function setNotifyBy($notifyBy)
    {
        $this->_notify = $notifyBy;
    }
   


    /**
     * Contact Person
     * @param string $value
     */
    public function setContactPerson($value)
    {
        $this->_properties['contactPerson'] = $value;
    }



    /**
     * Phone
     * @param string $value
     */
    public function setPhone($value)
    {
        if (is_string($value)) {
            // Only use numbers here
            $value = preg_replace("/[^0-9\+]/s", "", $value);

            // We need to have a correct phonenumer with +XX or 00XX.
            if (!(substr($value, 0, 2) == "00" || substr($value, 0, 1) == "+")) {
                $value = "+46" . substr($value, 1);
            }
            $this->_properties['phone'] = $value;
        }
    }


    /**
     * Mobile
     * @param string $value
     */
    public function setMobile($value)
    {
        if (is_string($value)) {
            // Only use numbers here
            $value = preg_replace("/[^0-9\+]/s", "", $value);

            // We need to have a correct phonenumer with +XX or 00XX.
            if (!(substr($value, 0, 2) == "00" || substr($value, 0, 1) == "+")) {
                $value = "+46" . substr($value, 1);
            }
            $this->_properties['mobile'] = $value;
        }
    }

    protected function _prepareMobile($value)
    {
        if ($this->_notify == self::MOBILE) {
            $value = array("_" => $value, "notify" => true);
        }
        return $value;
    }



    /**
     * Fax
     * @param string $value
     */
    public function setFax($value)
    {
        $this->_properties['fax'] = $value;
    }

    protected function _prepareFax($value)
    {
        if ($this->_notify == self::FAX) {
            $value = array("_" => $value, "notify" => true);
        }
        return $value;
    }


    /**
     * Email
     * @param string $value
     */
    public function setEmail($value)
    {
        $this->_properties['email'] = $value;
    }

    protected function _prepareEmail($value)
    {
        if ($this->_notify == self::EMAIL) {
            $value = array("_" => $value, "notify" => true);
        }
        return $value;
    }

     public function getEmail()
    {
        return (isset($this->_properties['email']))? $this->_properties['email'] : '';
    }

     public function getFax()
    {
        return (isset($this->_properties['fax']))? $this->_properties['fax'] : '';
    }

     public function getMobile()
    {
        return (isset($this->_properties['mobile']))? $this->_properties['mobile'] : '';
    }

         public function getContactPerson()
    {
        return (isset($this->_properties['contactPerson']))? $this->_properties['contactPerson'] : '';
    }
        public function getNotifyBy()
    {
        return $this->_notify;
    }
    
    public function getPhone()
    {
        return (isset($this->_properties['phone']))? $this->_properties['phone'] : '';
    }

}