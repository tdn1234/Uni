<?php

namespace Pcxpress\Unifaun\Controller\Adminhtml;


/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
class  PickupLocationController extends \Magento\Backend\App\Action
    //\Magento\Backend\App\Action
{

    /**
     * @var \Pcxpress\Unifaun\Model\PickupLocationFactory
     */
    protected $unifaunPickupLocationFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Pcxpress\Unifaun\Model\PickupAddressFactory
     */
    protected $unifaunPickupAddressFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\PickupLocationFactory $unifaunPickupLocationFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\Registry $registry,
        \Pcxpress\Unifaun\Model\PickupAddressFactory $unifaunPickupAddressFactory
    )
    {
        $this->unifaunPickupLocationFactory = $unifaunPickupLocationFactory;
        $this->backendSession = $backendSession;
        $this->registry = $registry;
        $this->unifaunPickupAddressFactory = $unifaunPickupAddressFactory;
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('unifaun/unifaun_pickuplocation')
            ->_addBreadcrumb(__('Pcxpress Unifaun: Pickup Locations'),
                __('Pickup Locations'));

        return $this;
    }

    public function execute()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_initAction();
        $id = $this->getRequest()->getParam('id');
        $model = $this->unifaunPickupLocationFactory->create()->load($id);

        /** @var \Magento\Backend\Model\Session $adminSession */
        $adminSession = $this->backendSession;

        if (!$model->getId() && $id != 0) {
            $adminSession->addError(__('Location does not exists!'));
            $this->_redirect('*/*/');

            return;
        }

        $data = $adminSession->getFormData(true);


        if (!empty($data)) {
            $model->setData($data);
        }

        $this->registry->register('pickuplocation_data', $model);

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('unifaun/adminhtml_pickupLocation_edit'))
            ->_addLeft($this->getLayout()->createBlock('unifaun/adminhtml_pickupLocation_edit_tabs'));

        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                $post = $this->getRequest()->getPost();

                $model = $this->unifaunPickupLocationFactory->create();
                $model->setData($post);
                $model->setId($this->getRequest()->getParam('id'));

                $model->save();

                $this->backendSession->addSuccess(__('Location saved successfully!'));
                $this->backendSession->setPickuAddressData(false);

                $this->_redirect('*/*/index');
                return;
            } catch (Exception $e) {
                $this->backendSession->addError($e->getMessage());
                $this->backendSession->setPickuAddressData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/index');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->unifaunPickupAddressFactory->create()->load($id);

        if ($model->getId() || $id == 0) {
            $model->delete();
            $this->backendSession->addSuccess(__('Location(s) deleted successfully!'));
            $this->_redirect('*/*/');
        } else {
            $this->backendSession->addError(__('Location does not exists!'));
            $this->_redirect('*/*/');
        }
    }
}