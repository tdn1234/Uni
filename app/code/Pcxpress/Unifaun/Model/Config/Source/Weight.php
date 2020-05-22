<?php

namespace Pcxpress\Unifaun\Model\Config\Source;

class Weight implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $weights = array();
        $weights[] = array(
            "value" => "kg",
            "label" => __("Kilograms (kg)"));
        $weights[] = array(
            "value" => "g",
            "label" => __("Grams (g)"));
        return $weights;
    }
}