<?php

namespace Pcxpress\Unifaun\Model\Config\Source;

use Pcxpress\Unifaun\Helper\Data;

class ShippingGroupSorting implements \Magento\Framework\Option\ArrayInterface
{
    protected $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function toOptionArray()
    {
        return $this->helper->getShippingGroupSortingArray();
    }
}