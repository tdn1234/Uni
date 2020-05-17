<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\SourceModel;

class WeightUnit
{
    public function toOptionArray()
    {
        $result = array();
        $result[] = array("value" => "kg", "label" => __("Kilograms (kg)"));
        $result[] = array("value" => "g", "label" => __("Grams (g)"));
        return $result;
    }
}