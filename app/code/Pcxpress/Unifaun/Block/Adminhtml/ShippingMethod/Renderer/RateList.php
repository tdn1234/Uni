<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingMethod\Renderer;

class RateList
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
		foreach ($row->getRates() as $price) {
				$prices[] = $price->getShippingFee();
		}
		if ($prices) {
				return implode(',&nbsp;', $prices);
		}

		return "<em>" . __('No Rates') . "</em>";
	}
}