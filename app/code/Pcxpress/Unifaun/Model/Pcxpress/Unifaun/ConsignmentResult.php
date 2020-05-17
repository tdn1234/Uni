<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class ConsignmentResult extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
    public function setStatusCode($value)
    {
        $this->_properties['statusCode'] = $value;
    }
    /**
     * Set Consignments
     * @param array $value
     */
    public function setConsignments($value)
    {
        $this->_properties['consignments'] = $value;
    }

    /**
     * Set Errors
     * @param array $value
     */
    public function setErrors($value)
    {
        $this->_properties['errors'] = $value;
    }

    /**
     * Set Consignments
     * @param array $value
     */
    public function setDocuments($value)
    {
        $this->_properties['documents'] = $value;
    }


    /**
     * Set Receipt
     * @param mixed $value
     */
    public function setReceipt($value)
    {
        $this->_properties['receipt'] = $value;
    }

    public function getStatusCode()
    {
        return (isset($this->_properties['statusCode']))? $this->_properties['statusCode'] : '';
    }

    public function getReceipt()
    {
        return (isset($this->_properties['receipt']))? $this->_properties['receipt'] : '';
    }

    public function getDocuments()
    {
        return (isset($this->_properties['documents']))? $this->_properties['documents'] : '';
    }

    public function getErrors()
    {
        return (isset($this->_properties['errors']))? $this->_properties['errors'] : '';
    }



    public function getConsignments()
    {
        return (isset($this->_properties['consignments']))? $this->_properties['consignments'] : '';
    }

    public function getFirstConsignmentNo()
    {
        $consignments = $this->getConsignments();
        $consignment_no = '';
        foreach ($consignments as $consignment) {
            return (isset($consignment['consignmentNo']))? $consignment['consignmentNo'] : '';
        }
        return $consignment_no;
    }

    public function getFirstConsignmentId()
    {
        $consignments = $this->getConsignments();
        $consignment_id = '';
        foreach ($consignments as $consignment) {
             return (isset($consignment['consignmentId']))? $consignment['consignmentId'] : '';
        }
        return $consignment_id;
    }

}