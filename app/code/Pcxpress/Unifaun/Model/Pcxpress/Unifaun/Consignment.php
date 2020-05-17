<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class Consignment extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{

    /**
     * Consignment number
     * @param string $value String 1..35
     */
    public function setConsignmentNo($value)
    {
        $this->_properties['consignmentNo'] = $value;
    }

    /**
     * Consignment id
     * @param string $value String 1..35
     */
    public function setConsignmentId($value)
    {
        $this->_properties['consignmentId'] = $value;
    }


    /**
     * Order number
     * @param string $value String 1..35
     */
    public function setOrderNo($value)
    {
        $this->_properties['orderNo'] = $value;
    }

    /**
     * Get Order type
     * @return string
     */
    public function getOrderType()
    {
        return (isset($this->_properties['orderType'])) ? $this->_properties['orderType'] : '';
    }

    /**
     * Order type
     * @param string $value String 1..35
     */
    public function setOrderType($value)
    {
        $this->_properties['orderType'] = $value;
    }

    /**
     * Automatic booking
     * @param string $value String, Y or N
     */
    public function setAutomaticBooking($value)
    {
        if ($value == "Y" || $value == "N") {
            $this->_properties['automaticBooking'] = $value;
        } else {
            throw new \Exception("Invalid input value");
        }
    }


    /**
     * Template name
     * @param string $value
     */
    public function setTemplateName($value)
    {
        $this->_properties['templateName'] = $value;
    }

    /**
     * Transport product
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProduct $value
     */
    public function setTransportProduct(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProduct $value)
    {
        $this->_properties['TransportProduct'] = $value;
    }

    /**
     * Target
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Target $value
     */
    public function setTarget(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Target $value)
    {
        $this->_properties['Target'] = $value;
    }


    /**
     * Contents
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Contents $value
     */
    public function setContents(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Contents $value)
    {
        $this->_properties['Contents'] = $value;
    }


    /**
     * Goods invoice
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsInvoice $value
     */
    public function setGoodsInvoice(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsInvoice $value)
    {
        $this->_properties['GoodsInvoice'] = $value;
    }

    /**
     * Add Consignment Reference
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentReference $item
     */
    public function addConsignmentReference(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentReference $item)
    {
        $this->_properties['ConsignmentReference'][] = $item;
    }


    /**
     * Clear Consignment References
     */
    public function clearConsignmentReferences()
    {
        $this->_properties['ConsignmentReference'] = array();
    }

    /**
     * Add Part
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Part $item
     */
    public function addPart(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Part $item)
    {
        $this->_properties['Part'][] = $item;
    }


    /**
     * Clear Parts
     */
    public function clearParts()
    {
        $this->_properties['Part'] = array();
    }

    /**
     * Add Goods Item
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsItem $item
     */
    public function addGoodsItem(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsItem $item)
    {
        $this->_properties['GoodsItem'][] = $item;
    }


    /**
     * Clear Goods Items
     */
    public function clearGoodsItems()
    {
        $this->_properties['GoodsItem'] = array();
    }


    public function clearInsurance()
    {
        $this->_properties['Insurance'] = array();
    }


    /**
     * Add Note
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Note $item
     */
    public function addNote(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Note $item)
    {
        $this->_properties['Note'][] = $item;
    }

    /**
     * Get Notes
     * @return array
     */
    public function getNotes()
    {
        return (isset($this->_properties['Note'])) ? $this->_properties['Note'] : '';
    }

    public function getTransportProduct()
    {
        return (isset($this->_properties['TransportProduct'])) ? $this->_properties['TransportProduct'] : '';
    }

    public function getTemplateName()
    {
        return (isset($this->_properties['templateName'])) ? $this->_properties['templateName'] : '';
    }

    public function getAutomaticBooking()
    {
        return (isset($this->_properties['automaticBooking'])) ? $this->_properties['automaticBooking'] : '';
    }

    public function getOrderNo()
    {
        return (isset($this->_properties['orderNo'])) ? $this->_properties['orderNo'] : '';
    }

    public function getConsignmentId()
    {
        return (isset($this->_properties['consignmentId'])) ? $this->_properties['consignmentId'] : '';
    }

    public function getConsignmentNo()
    {
        return (isset($this->_properties['consignmentNo'])) ? $this->_properties['consignmentNo'] : '';
    }

    public function getTarget()
    {
        return (isset($this->_properties['Target'])) ? $this->_properties['Target'] : '';
    }

    public function getContents()
    {
        return (isset($this->_properties['Contents'])) ? $this->_properties['Contents'] : '';
    }


    public function getGoodsInvoice()
    {
        return (isset($this->_properties['GoodsInvoice'])) ? $this->_properties['GoodsInvoice'] : '';
    }

    public function getConsignmentReferences()
    {
        return (isset($this->_properties['ConsignmentReference'])) ? $this->_properties['ConsignmentReference'] : '';
    }

    public function getParts()
    {
        return (isset($this->_properties['Part'])) ? $this->_properties['Part'] : '';
    }

    public function getGoodsItems()
    {
        return (isset($this->_properties['GoodsItem'])) ? $this->_properties['GoodsItem'] : '';
    }

    /**
     * Clear Notes
     */
    public function clearNotes()
    {
        $this->_properties['Note'] = array();
    }

}