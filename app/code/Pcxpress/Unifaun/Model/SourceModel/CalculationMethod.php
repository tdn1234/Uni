<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\SourceModel;

class CalculationMethod
{
    public function toOptionArray()
    {
        $result = array();
        $result[] = array("value" => "unifaun/carrier_calculationMethod_weight", "label" => __("Based on weight"));
        $result[] = array("value" => "unifaun/carrier_calculationMethod_unit", "label" => __("Based on product configuration (unit)"));

        $dir = dirname(__FILE__) . "/../Carrier/CalculationMethod/CustomerSpecific";
        foreach (glob($dir . "/*") as $file) {
            $filename = basename($file, ".php");
            $result[] = array("value" => "unifaun/carrier_calculationMethod_customerSpecific_" . $filename, "label" => $filename);
        }

        return $result;
    }
}