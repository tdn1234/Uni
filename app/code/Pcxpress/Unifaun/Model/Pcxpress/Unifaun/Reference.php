<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class Reference extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
    protected $_namespaceForProperties = array("reference" => "http://www.spedpoint.com/consignment/types/v1_0");

    public function setReference($value)
    {
        $this->_properties['reference'] = $value;
    }

    public function getReference()
    {
        return (isset($this->_properties['reference']))? $this->_properties['reference'] : '';
    }



}