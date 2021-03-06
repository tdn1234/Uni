<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml;

class PickupLocation extends \Magento\Backend\Block\Widget\Grid\Container
{
	/**
	 * Constructor
	 * @param null	
	 * @return null
	 */
	public function __construct()
	{
		$this->_controller = 'adminhtml_pickupLocation';
		$this->_blockGroup = 'unifaun';
		$this->_headerText = __('Pcxpress Unifaun: Pickup Location');
		parent::__construct();
	}
}