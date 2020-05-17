<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\SourceModel\Cod;

class PaymentMethod
{
    public function toOptionArray()
    {
        $result = array();
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod::PAYMENTMETHOD_CASH, "label" => "Cash");
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod::PAYMENTMETHOD_CHEQUE, "label" => "Cheque");
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod::PAYMENTMETHOD_POST, "label" => "Post");
        $result[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod::PAYMENTMETHOD_BANK, "label" => "Bank");

        return $result;
    }
}