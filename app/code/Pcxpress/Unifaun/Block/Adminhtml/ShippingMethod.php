<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml;

class ShippingMethod extends \Magento\Backend\Block\Widget\Grid\Container
{
	/**
	 * Constructor
	 * @param null	
	 * @return null
	 */
	public function __construct()
	{
		$this->_blockGroup = 'unifaun';
		$this->_controller = 'adminhtml_shippingMethod';		
		$this->_headerText = Mage::helper('core')->__('Shipping Methods');
		$this->_addButtonLabel = Mage::helper('core')->__('Add Shipping Method');
		parent::__construct(); 
	}
}