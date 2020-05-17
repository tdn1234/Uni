<?php
namespace Pcxpress\Unifaun\Controller\Adminhtml\Unifaun;


/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
class PickupAddressController extends \Magento\Backend\App\Action
{

    /**
     * @var \Pcxpress\Unifaun\Model\PickupAddressFactory
     */
    protected $unifaunPickupAddressFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Pcxpress\Unifaun\Model\PickupAddressFactory $unifaunPickupAddressFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\Registry $registry
    ) {
        $this->unifaunPickupAddressFactory = $unifaunPickupAddressFactory;
        $this->backendSession = $backendSession;
        $this->registry = $registry;
    }
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/unifaun_adminform/unifaun_pickupaddress')
                ->_addBreadcrumb(__('Pcxpress Unifaun: Pickup Addresses'),
                __('Pickup Addresses'));

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
        $model = $this->unifaunPickupAddressFactory->create()->load($id);

        /** @var \Magento\Backend\Model\Session $adminSession */
        $adminSession = $this->backendSession;

        if (!$model->getId() && $id != 0) {
            $adminSession->addError(__('Item does not exist'));
            $this->_redirect('*/*/');

            return;
        }

        $data = $adminSession->getFormData(true);


        if (!empty($data)) {
            $model->setData($data);
        }

        $this->registry->register('pickupaddress_data', $model);

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('unifaun/adminhtml_pickupAddress_edit'))
            ->_addLeft($this->getLayout()->createBlock('unifaun/adminhtml_pickupAddress_edit_tabs'));

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
                $postData = $this->getRequest()->getPost();
                /** @var $model Pcxpress_Unifaun_Model_PickupAddress */
                $model = $this->unifaunPickupAddressFactory->create();

                $model->setId($this->getRequest()->getParam('id'))
                    ->setAddressName(trim($postData['address_name']))
                    ->setAddressAddress1(trim($postData['address_address1']))
                    ->setAddressAddress2(trim($postData['address_address2']))
                    ->setAddressAddress3(trim($postData['address_address3']))
                    ->setAddressPostcode(trim($postData['address_postcode']))
                    ->setAddressCity(trim($postData['address_city']))
                    ->setAddressState(trim($postData['address_state']))
                    ->setAddressCountrycode(trim(strtoupper($postData['address_countrycode'])))
                    ->setCommunicationContactPerson(trim($postData['communication_contact_person']))
                    ->setCommunicationPhone(trim($postData['communication_phone']))
                    ->setCommunicationMobile(trim($postData['communication_mobile']))
                    ->setCommunicationFax(trim($postData['communication_fax']))
                    ->setCommunicationEmail(trim($postData['communication_email']));

                $model->save();

                $this->backendSession->addSuccess(__('Item was successfully saved'));
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
            $this->backendSession->addSuccess(__('Field were successfully deleted'));
            $this->_redirect('*/*/');
        } else {
            $this->backendSession->addError(__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }
}