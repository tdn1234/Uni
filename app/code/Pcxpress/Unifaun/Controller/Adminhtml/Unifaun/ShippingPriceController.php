<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Controller\Adminhtml\Unifaun;

class ShippingPriceController  extends \Magento\Backend\App\Action {

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingPriceFactory
     */
    protected $unifaunShippingPriceFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Pcxpress\Unifaun\Model\ShippingPriceFactory $unifaunShippingPriceFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\Registry $registry
    ) {
        $this->unifaunShippingPriceFactory = $unifaunShippingPriceFactory;
        $this->backendSession = $backendSession;
        $this->registry = $registry;
    }
    protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('system/unifaun_adminform/unifaun_shippingmethod')
                ->_addBreadcrumb(__('Pcxpress Unifaun: Shipping Prices'), __('Price Manager'));

        return $this;
    }

    public function execute()
    {
        $this->_initAction()->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->unifaunShippingPriceFactory->create()->load($id);

        if ($model->getId() || $id == 0) {
            $data = $this->backendSession->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            $this->registry->register('shippingprice_data', $model);

            $this->loadLayout();
            //$this->_setActiveMenu('unifaun/shippingMethod');
            //$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Pcxpress Unifaun: Shipping Methods'), Mage::helper('adminhtml')->__('Item Manager'));
            //$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('unifaun/adminhtml_shippingPrice_edit'))
                    ->_addLeft($this->getLayout()->createBlock('unifaun/adminhtml_shippingPrice_edit_tabs'));

            $this->renderLayout();
        } else {
            $this->backendSession->addError(__('Item does not exist'));
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
        $model = $this->unifaunShippingPriceFactory->create()->load($id);

        if ($model->getId() || $id == 0) {
            $method = $model->getShippingmethodId();
            $model->delete();
            $this->backendSession->addSuccess(__('Field were successfully deleted'));
            $this->_redirect('*/*/', array("method" => $method));
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
                $shippingPriceModel = $this->unifaunShippingPriceFactory->create();

                $countries = $this->_getValue($postData, 'country');
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
                
                $shippingPriceModel->setId($this->getRequest()->getParam('id'))
                        ->setWeightMax($this->_getValue($postData, 'weight_max'))
                        ->setWidthMax($this->_getValue($postData, 'width_max'))
                        ->setHeightMax($this->_getValue($postData, 'height_max'))
                        ->setDepthMax($this->_getValue($postData, 'depth_max'))
                        ->setZipcodeRanges($this->_getValue($postData, 'zipcode_ranges'))
                        ->setCountries($countries)
                        ->setWebsiteId($this->_getValue($postData, 'website_id'))
                        ->setShippingFee($postData['shipping_fee'])
                        ->setShippingmethodId($this->getRequest()->getParam("method"))
                        ->save();

                $this->backendSession->addSuccess(__('Item was successfully saved'));
                $this->backendSession->setShippingpriceData(false);

                $this->_redirect('*/*/index', array("method" => $this->getRequest()->getParam("method")));
                return;
            } catch (Exception $e) {
                $this->backendSession->addError($e->getMessage());
                $this->backendSession->setShippingpriceData($this->getRequest()->getPost());
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