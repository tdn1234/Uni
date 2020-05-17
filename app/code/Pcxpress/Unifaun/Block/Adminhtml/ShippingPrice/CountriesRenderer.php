<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingPrice;


class CountriesRenderer
    extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(\Magento\Framework\DataObject $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        if (is_array($value) && count($value)) {
            if (count($value) > 10) {
                return __('%s and %d more', join(', ', array_slice($value, 0, 10)), count($value) - 10);
            } else {
                return join(', ', $value);
            }
        }
        return __('(all)');
    }


}