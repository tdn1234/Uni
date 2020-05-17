<?php
/**
 *  Pcxpress Unifaun integration
 * Author Name : Dev team Pcxpress
 * Author Emails:info@pcxpress.se
 *  Copyright (c) 2011 - Pcxpress
 * www.webdesignhuset.se
 */

namespace Pcxpress\Unifaun\Controller\Adminhtml\Unifaun;

class ShippingMethodController extends \Magento\Backend\App\Action
{

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
        $this->backendSession = $backendSession;
        $this->registry = $registry;
        $this->storeManager = $storeManager;
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/unifaun_adminform/unifaun_shippingmethod')
            ->_addBreadcrumb(__('Pcxpress Unifaun: Shipping Methods'), __('Item Manager'));

        return $this;
    }

    public function execute()
    {
        $this->_initAction()->renderLayout();
    }


    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->unifaunShippingMethodFactory->create()->load($id);

        if ($model->getId() || $id == 0) {
            $data = $this->backendSession->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            $this->registry->register('shippingmethod_data', $model);

            $this->loadLayout();

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('unifaun/adminhtml_shippingMethod_edit'))
                ->_addLeft($this->getLayout()->createBlock('unifaun/adminhtml_shippingMethod_edit_tabs'));

            $this->renderLayout();
        } else {
            $this->backendSession->addError(__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->unifaunShippingMethodFactory->create()->load($id);

        if ($model->getId() || $id == 0) {
            $model->delete();
            $this->backendSession->addSuccess(__('Field were successfully deleted'));
            $this->_redirect('*/*/');
        } else {
            $this->backendSession->addError(__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                $postData = $this->getRequest()->getPost();
                $shippingMethodModel = $this->unifaunShippingMethodFactory->create();
                /** @var $shippingMethodModel Pcxpress_Unifaun_Model_ShippingMethod */
                $shippingMethodModel->setId($this->getRequest()->getParam('id'))
                    ->setTitle($postData['title'])
                    ->setTemplateName($postData['template_name'])
                    ->setDescription($postData['description'])
                    ->setFreeShippingEnable($postData['free_shipping_enable'])
                    ->setFreeShippingSubtotal($postData['free_shipping_subtotal'])
                    ->setHandlingFee($postData['handling_fee'])
                    ->setLastBookingTime($postData['last_booking_time'])
                    ->setVisibleFrontend($postData['visible_frontend'])
                    ->setNoBooking($postData['no_booking'])
                    ->setMinConsignmentWeight(floatval($postData['min_consignment_weight']))
                    ->setMaxConsignmentWeight(floatval($postData['max_consignment_weight']))
                    ->setOnlyLabel($postData['only_label'])
                    ->setActivated($postData['activated'])
                    ->setShippingGroup($postData['shipping_group'])
                    ->setConsolidationEnable(intval($postData['consolidation_enable']))
                    ->setConsolidationPriority(intval($postData['consolidation_priority']))
                    ->setMultipleParcels(intval($postData['multiple_parcels']))
                    ->setConsolidationProductId($postData['consolidation_product_id'])
                    ->setDefaultAdvice($postData['advice_default'])
                    ->setStoreIds(array_key_exists('store_ids', $postData) ? $postData['store_ids'] : array());

                if (Mage::app()->isSingleStoreMode()) {
                    $shippingMethodModel->setStoreIds(array($this->storeManager->getStore(true)->getId()));
                }

                $shippingMethodModel->save();

                $this->backendSession->addSuccess(__('Item was successfully saved'));
                $this->backendSession->setShippingmethodData(false);

                $this->_redirect('*/*/index');
                return;
            } catch (Exception $e) {
                $this->backendSession->addError($e->getMessage());
                $this->backendSession->setShippingmethodData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/index');
    }

}