<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Controller\Adminhtml\Unifaun;

class UnificationMatrixController extends \Magento\Backend\App\Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('unifaun/shippingMethod')
            ->_addBreadcrumb(__('Pcxpress Unifaun: Shipping Methods'), __('Matrix Manager'));

        return $this;
    }

    public function execute()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('unifaun/adminhtml_unificationMatrix'));
        $this->renderLayout();
    }

}