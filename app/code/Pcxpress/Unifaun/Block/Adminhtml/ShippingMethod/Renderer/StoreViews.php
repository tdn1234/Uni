<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingMethod\Renderer;

class StoreViews
    extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $storeSystemStore;

    public function __construct(
        \Magento\Store\Model\System\Store $storeSystemStore
    ) {
        $this->storeSystemStore = $storeSystemStore;
    }
    /**
	 * render
	 * @param \Magento\Framework\DataObject $row
	 * @return string
	 */
	public function render(\Magento\Framework\DataObject $row)
	{
		$options = $this->storeSystemStore->getStoreOptionHash();
		
		$values = $row->getData($this->getColumn()->getIndex());
		if (is_array($values) && count($values)) {
			if (count($values) > 1 && in_array(0, $values)) {
				return __('(all)');
			} else {
				$result = array();
				foreach ($values as $value) {
						if (array_key_exists($value, $options)) {
								$result[] = $options[$value];
						}
				}
				if (!count($result)) {
						return null;
				}
				sort($result);
				return join("<br>", $result);
			}
		}
		return __('?');
	}
}