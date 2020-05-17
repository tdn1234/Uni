<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\PickupLocation\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
	/**
	 * Constructor
	 * @param null	
	 * @return null
	 */	
	public function __construct()
	{
		parent::__construct();
		$this->setId('pickuplocation_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(__('Pcxpress Unifaun'));
	}
	
	/**
	 * _beforeToHtml
	 * @param null	
	 * @return object
	 */
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
			'label'     => __('Pickup Location'),
			'title'     => __('Pickup Location'),
			'content'   => $this->getLayout()->createBlock('\Pcxpress\Unifaun\Block\Adminhtml\PickupLocation\Edit\Tab\Form')->toHtml(),
		));

		return parent::_beforeToHtml();
	}
}