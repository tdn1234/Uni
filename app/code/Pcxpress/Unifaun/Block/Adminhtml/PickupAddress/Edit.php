<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\PickupAddress;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Framework\Registry $registry
    )
    {
        $this->registry = $registry;
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'unifaun';
        $this->_controller = 'adminhtml_pickupAddress';

        $this->_updateButton('save', 'label', __('Save Address'));
        $this->_updateButton('delete', 'label', __('Delete Address'));

    }

    public function getHeaderText()
    {
        if( $this->registry->registry('pickupaddress_data') && $this->registry->registry('pickupaddress_data')->getId() ) {
            return __("Edit Pickup Address '%s'", $this->escapeHtml($this->registry->registry('pickupaddress_data')->getAddressAddress()));
        } else {
            return __('Add Pickup Address');
        }
    }
}