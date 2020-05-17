<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\System\Config;


class Separator
    extends \Magento\Backend\Block\AbstractBlock
//    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $label = $element->getLabelHtml();

        return "
        <tr>
            <td colspan='2' style='padding: 10px 5px 8px;'>
                <div style='border-bottom: 1px solid #d6d6d6; text-transform: uppercase; font-weight: bold;'>$label</div>
            </td>
        </tr>";
    }
}