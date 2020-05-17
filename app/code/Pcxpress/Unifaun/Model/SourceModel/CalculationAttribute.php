<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\SourceModel;

class CalculationAttribute
{
    public function toOptionArray()
    {
        $result = array();
        $result[] = array("value" => "weight_max", "label" => __("Vikt"));
        $result[] = array("value" => "width_max", "label" => __("Bredd"));
        return $result;
    }
}