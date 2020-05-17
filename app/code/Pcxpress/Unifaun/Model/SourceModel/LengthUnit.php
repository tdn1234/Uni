<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\SourceModel;

class LengthUnit
{
    public function toOptionArray()
    {
        $result = array();
        $result[] = array("value" => "cm", "label" => __("Centimeters"));
        return $result;
    }
}