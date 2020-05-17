<?php
/**
 *  Pcxpress Unifaun integration
    Author Name : Dev team Pcxpress
    Author Emails:info@pcxpress.se
 *  Copyright (c) 2011 - Pcxpress
 * www.webdesignhuset.se
 */
namespace Pcxpress\Unifaun\Controller\Adminhtml;

class ShippingMethodController  extends \Magento\Backend\App\Action {

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

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingRateFactory
     */
    protected $unifaunShippingRateFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Pcxpress\Unifaun\Model\ShippingRateFactory $unifaunShippingRateFactory
    ) {
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
        $this->backendSession = $backendSession;
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->unifaunShippingRateFactory = $unifaunShippingRateFactory;
    }
    protected function _initAction()
	{
		$this->loadLayout()
					->_setActiveMenu('unifaun/unifaun_shippingmethod')
					->_addBreadcrumb(__('Pcxpress Unifaun: Shipping Methods'), __('Shipping Method Manager'));

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
			$this->backendSession->addError(__('Method does not exists!'));
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
			$this->backendSession->addSuccess(__('Method(s) deleted successfully!'));
			$this->_redirect('*/*/');
		} else {
			$this->backendSession->addError(__('Method does not exists!'));
			$this->_redirect('*/*/');
		}
	}

	public function saveAction()
	{
		if ($this->getRequest()->getPost()) {
			try {
				$post = $this->getRequest()->getPost();

				$shippingMethodModel = $this->unifaunShippingMethodFactory->create(); 
				$shippingMethodModel->setData($post);
				$shippingMethodModel->setId($this->getRequest()->getParam('id'));
				$shippingMethodModel->setStoreIds(array_key_exists('store_ids', $post) ? $post['store_ids'] : array());
				
				
				if (Mage::app()->isSingleStoreMode()) {
						$shippingMethodModel->setStoreIds(array($this->storeManager->getStore(true)->getId()));
				}
				
				$shippingMethodModel->save();

				$this->backendSession->addSuccess(__('Method saved successfully!'));
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
	
	public function saveMethodRate($shippingMethodId)
	{
		
			try {
				$post = $this->getRequest()->getPost();
				$shippingRateModel = $this->unifaunShippingRateFactory->create();

				if(isset($post['country'])){
				$countries = $post['country'];
				
				if (is_array($countries)) {
					foreach ($countries as $key => $countryCode) {
						if (!preg_match('/^[A-Z]{2,3}$/s', $countryCode)) {
							unset($countries[$key]);
						}
					}
					$countries = array_values($countries);
				} else {
					$countries = array();
				}
				}
				else{
					$countries = array();
				}
				
				//$shippingRateModel->setData($post);
				if($post['shippingrate_id']){
					$shippingRateModel->setId($post['shippingrate_id']);
				}
				$shippingRateModel->setMaxWeight($post['max_weight']);
				$shippingRateModel->setMaxWidth($post['max_width']);
				$shippingRateModel->setMaxHeight($post['max_height']);
				$shippingRateModel->setDepthMax($post['max_depth']);
				$shippingRateModel->setZipcodeRange($post['zipcode_range']);
				if(isset($post['website_id'])){
					$shippingRateModel->setWebsiteId($post['website_id']);
				}
				$shippingRateModel->setShippingFee($post['shipping_fee']);
				
		
				
				$shippingRateModel->setShippingmethodId($shippingMethodId);
				$shippingRateModel->setCountries($countries);

				$shippingRateModel->save();
				return;
			} catch (Exception $e) {
				return __('Something went wrong while saving the rate!');
			}
		
	}
}