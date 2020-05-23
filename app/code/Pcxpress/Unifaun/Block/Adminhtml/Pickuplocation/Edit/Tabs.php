<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\Pickuplocation\Edit;

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
        $this->setId('pickuplocation_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Pickuplocation Information'));
    }
}