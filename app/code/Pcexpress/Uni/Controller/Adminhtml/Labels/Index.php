<?php

namespace Pcexpress\Uni\Controller\Adminhtml\Labels;


class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
        var_dump('sdfsdfsdf lablelel');
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
        var_dump($this->_view->isLayoutLoaded());
        var_dump(get_class_methods($this->_view));die;
	}
}