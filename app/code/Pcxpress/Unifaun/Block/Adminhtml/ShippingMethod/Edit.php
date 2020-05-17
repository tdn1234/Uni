<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingMethod;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
	 * Constructor
	 * @param null	
	 * @return null
	 */	
	public function __construct(
        \Magento\Framework\Registry $registry
    )
	{
        $this->registry = $registry;
		parent::__construct();
		$this->_objectId = 'id';
		$this->_blockGroup = 'unifaun';
		$this->_controller = 'adminhtml_shippingMethod';

		$this->_updateButton('save', 'label', __('Save Method'));
		$this->_updateButton('delete', 'label', __('Delete Method'));

	}
	
	/**
	 * getHeaderText
	 * @param bull	
	 * @return string
	 */
	public function getHeaderText()
	{
		if( $this->registry->registry('shippingmethod_data') && $this->registry->registry('shippingmethod_data')->getId() ) {
				return __("Edit Shipping Method '%s'", $this->escapeHtml($this->registry->registry('shippingmethod_data')->getTitle()));
		} else {
				return __('Add Shipping Method');
		}
	}
}