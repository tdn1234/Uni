<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Block\Adminhtml\System\Config;


class SectionHeading
    extends \Magento\Backend\Block\AbstractBlock
//    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '';
        $label = $element->getLabelHtml();
        $html .= '<tr>';
        $html .= '<td colspan="2" style="padding: 10px 0px 10px;">';
        $html .= '<span style="border-bottom: 1.5px solid #c8c8c8; text-transform: uppercase; font-weight: bold;">' . $label . '</span>';
        $html .= '</td></tr>';

        return $html;
    }
}