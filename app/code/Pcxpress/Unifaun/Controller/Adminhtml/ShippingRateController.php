<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Controller\Adminhtml\Unifaun;

class ShippingRateController extends \Magento\Backend\App\Action {

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingRateFactory
     */
    protected $unifaunShippingRateFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Pcxpress\Unifaun\Model\ShippingRateFactory $unifaunShippingRateFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\Registry $registry
    ) {
        $this->unifaunShippingRateFactory = $unifaunShippingRateFactory;
        $this->backendSession = $backendSession;
        $this->registry = $registry;
    }
    protected function _initAction()
	{
		$this->loadLayout()
					->_setActiveMenu('unifaun/unifaun_shippingmethod')
					->_addBreadcrumb(__('Pcxpress Unifaun: Shipping Rates'), __('Rate Manager'));
		return $this;
	}

	public function execute()
	{
		$this->_initAction()->renderLayout();
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = $this->unifaunShippingRateFactory->create()->load($id);

		if ($model->getId() || $id == 0) {
			
			$data = $this->backendSession->getFormData(true);
			if (!empty($data)) {
					$model->setData($data);
			}

			$this->registry->register('shippingrate_data', $model);
			
			$this->_title($this->__('Shipping Method'))
             ->_title($this->__('Rates'));

      $this->_title($this->__('Edit Rate'));
			$this->loadLayout();
			
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('unifaun/adminhtml_shippingRate_edit'))
							->_addLeft($this->getLayout()->createBlock('unifaun/adminhtml_shippingRate_edit_tabs'));

			$this->renderLayout();
		} else {
			$this->backendSession->addError(__('Rate does not exist'));
			$this->_redirect('*/*/', array("method" => $this->getRequest()->getParam("method")));
		}
	}

	public function newAction()
	{
		$this->_forward('edit');
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = $this->unifaunShippingRateFactory->create()->load($id);

		if ($model->getId() || $id == 0) {
			$method = $model->getShippingmethodId();
			$model->delete();
			$this->backendSession->addSuccess(__('Rate(s) deleted successfully!'));
			$this->_redirect('*/*/', array("method" => $method));
		} else {
			$this->backendSession->addError(__('Rate does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function saveAction()
	{
		if ($this->getRequest()->getPost()) {
			try {
				$post = $this->getRequest()->getPost();
				$shippingRateModel = $this->unifaunShippingRateFactory->create();

				$countries = $this->_getValue($post, 'country');
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
				
				
				/*$shippingRateModel->setId($this->getRequest()->getParam('id'))
								->setMaxWeight($this->_getValue($post, 'max_weight'))
								->setMaxWidth($this->_getValue($post, 'max_width'))
								->setMaxHeight($this->_getValue($post, 'max_height'))
								->setDepthMax($this->_getValue($post, 'max_depth'))
								->setZipcodeRange($this->_getValue($post, 'zipcode_range'))
								->setCountries($countries)
								->setWebsiteId($this->_getValue($post, 'website_id'))
								->setShippingFee($post['shipping_fee'])
								->setShippingmethodId($this->getRequest()->getParam("method"))
					*/
				$shippingRateModel->setData($post);
				$shippingRateModel->setId($this->getRequest()->getParam('id'));				
				$shippingRateModel->setShippingmethodId($this->getRequest()->getParam("method"));
				$shippingRateModel->setCountries($countries);
				

				$shippingRateModel->save();
				
				$this->backendSession->addSuccess(__('Rate was successfully saved'));
				$this->backendSession->setShippingrateData(false);

				$this->_redirect('*/*/index', array("method" => $this->getRequest()->getParam("method")));
				return;
			} catch (Exception $e) {
				$this->backendSession->addError($e->getMessage());
				$this->backendSession->setShippingrateData($this->getRequest()->getPost());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'method' => $this->getRequest()->getParam("method")));
				return;
			}
		}
		$this->_redirect('*/*/index', array("method" => $this->getRequest()->getParam("method")));
	}

	protected function _getValue(array $array, $key, $default = null)
	{
		if (!array_key_exists($key, $array)) {
			return $default;
		}
		return $array[$key];
	}

}