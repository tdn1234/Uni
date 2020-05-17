<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnifaunMatrix\Renderer;

class ConsolidationPriority extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(\Magento\Framework\DataObject $row)
    {
        /** @var $row Pcxpress_Unifaun_Model_ShippingMethod */
        if (!$row->getConsolidationPriority()) {
            return '-';
        }

        return $row->getConsolidationPriority();
    }

}