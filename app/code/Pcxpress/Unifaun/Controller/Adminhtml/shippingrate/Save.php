<?php
namespace Pcxpress\Unifaun\Controller\Adminhtml\shippingrate;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Session\SessionManagerInterface;

class Save extends \Magento\Backend\App\Action
{

    /** @var SessionManagerInterface $sessionManager */
    protected $sessionManager;
    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context, SessionManagerInterface $sessionManager)
    {
        $this->sessionManager = $sessionManager;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data['shippingmethod_id']) {
            $data['shippingmethod_id'] = $this->sessionManager->getData('shippingmethod_id');
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('Pcxpress\Unifaun\Model\Shippingrate');

            $id = $this->getRequest()->getParam('shippingrate_id');
            if ($id) {
                $model->load($id);
                $model->setCreatedAt(date('Y-m-d H:i:s'));
            }


            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Shippingrate has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['shippingrate_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/', array('shippingmethod_id' => $data['shippingmethod_id']));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Shippingrate.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['shippingrate_id' => $this->getRequest()->getParam('shippingrate_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}