<?php
namespace Pcexpress\Unix\Block\Adminhtml\Unifaunlabels\Edit;

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
        $this->setId('unifaunlabels_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Unifaunlabels Information'));
    }
}