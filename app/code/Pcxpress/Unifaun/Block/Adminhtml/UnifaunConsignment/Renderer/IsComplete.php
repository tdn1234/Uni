<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnifaunConsignment\Renderer;

class IsComplete extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(\Magento\Framework\DataObject $row)
    {
    	$html = '';        
        if ($row->getShipmentStatus() == 1) {
            $html = "<i class='fa fa-check-circle'></i>";
        } else {
            $html = "<i class='fa fa-question-circle'></i>";
        }
        return $html;
    }

}