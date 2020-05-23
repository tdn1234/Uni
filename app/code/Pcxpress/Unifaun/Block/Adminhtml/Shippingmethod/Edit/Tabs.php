<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('shippingmethod_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Shippingmethod Information'));
    }
}