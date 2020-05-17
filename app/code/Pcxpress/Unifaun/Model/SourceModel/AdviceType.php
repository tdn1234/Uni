<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\SourceModel;

class AdviceType
{
    public function toOptionArray()
    {
        $result = array();
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::NOADVICE, "label" => __("None"));
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::PHONE, "label" => __("Phone"));
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::POSTAL, "label" => __("Postal"));
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::MOBILE, "label" => __("Cellphone"));
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::FAX, "label" => __("Fax"));
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::EMAIL, "label" => __("E-mail"));

        return $result;
    }
}