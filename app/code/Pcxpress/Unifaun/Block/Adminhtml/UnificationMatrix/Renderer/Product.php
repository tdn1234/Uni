<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnificationMatrix\Renderer;

class Product extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory
    ) {
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
    }
    public function render(\Magento\Framework\DataObject $row)
	{
		$shippingMethodTitle = Mage::helper('core')->__('None');
		if (!$row->getUnificationProductId()) {			
			$shippingMethod = $this->unifaunShippingMethodFactory->create()->load($row->getUnificationProductId()); 
			
			if ($shippingMethod && $shippingMethod->getTitle()) {
				$shippingMethodTitle = $shippingMethod->getTitle();
			}			
		}
		return $shippingMethodTitle;
	}

}