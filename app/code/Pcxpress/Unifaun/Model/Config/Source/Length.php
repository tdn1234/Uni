<?php

namespace Pcxpress\Unifaun\Model\Config\Source;

class Length implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $lengths = array();
        $lengths[] = array(
            "value" => "cm",
            "label" => __("Centimeters")
        );
        return $lengths;
    }
}