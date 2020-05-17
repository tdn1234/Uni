<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\PickupAddress\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('pickupaddress_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Pcxpress Unifaun'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => __('Pickup Address'),
            'title'     => __('Pickup Address'),
            'content'   => $this->getLayout()->createBlock('\Pcxpress\Unifaun\Block\Adminhtml\PickupAddress\Edit\Tab\Form')->toHtml(),
        ));


        return parent::_beforeToHtml();
    }
}