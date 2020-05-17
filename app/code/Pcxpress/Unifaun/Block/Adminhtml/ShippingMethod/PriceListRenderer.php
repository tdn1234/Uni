<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingMethod;


class PriceListRenderer
    extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        /* @var $row Pcxpress_Unifaun_Model_ShippingMethod */

        $prices = array();
        foreach ($row->getPrices() as $price) {
            $prices[] = $price->getShippingFee();
        }
        if ($prices) {
            return implode(',&nbsp;', $prices);
        }

        return "<em>" . __('No prices') . "</em>";
    }


}