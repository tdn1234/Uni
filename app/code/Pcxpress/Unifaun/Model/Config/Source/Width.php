<?php

namespace Pcxpress\Unifaun\Model\Config\Source;

use Pcxpress\Unifaun\Helper\Data;

class Width implements \Magento\Framework\Option\ArrayInterface
{
    protected $helper;

    public function __construct(Data $helper)
    {
       $this->helper = $helper;
    }

    public function toOptionArray()
    {
        return $this->helper->getProductShippingArray();
    }

//    public function toOptionArray()
//    {
//        $entityTypeId = $this->eavEntityFactory->create()->setType('catalog_product')->getTypeId();
//        $attributes = $this->eavResourceModelEntityAttributeCollectionFactory->create()->setEntityTypeFilter($entityTypeId);
//
//        $shippings = array();
//        $shippings[] = array(
//            "value" => "",
//            "label" => __("Not used")
//        );
//        foreach ($attributes as $attribute) {
//            $label = __($attribute->getFrontendLabel());
//            if (!$label) {
//                $label = $attribute->getAttributeCode();
//            }
//            $shippings[] = array(
//                "value" => $attribute->getAttributeCode(),
//                "label" => $label);
//        }
//        return $shippings;
//    }
}