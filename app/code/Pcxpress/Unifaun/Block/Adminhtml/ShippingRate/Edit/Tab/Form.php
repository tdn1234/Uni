<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingRate\Edit\Tab;

class Form extends \Magento\Backend\Block\Widget\Form {

    /**
     * @var \Magento\Config\Model\Config\Source\WebsiteFactory
     */
    protected $configConfigSourceWebsiteFactory;

    /**
     * @var \Magento\Directory\Model\Config\Source\CountryFactory
     */
    protected $directoryConfigSourceCountryFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $formFactory;

    public function __construct(
        \Magento\Config\Model\Config\Source\WebsiteFactory $configConfigSourceWebsiteFactory,
        \Magento\Directory\Model\Config\Source\CountryFactory $directoryConfigSourceCountryFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory
    )
	{
        $this->formFactory = $formFactory;
        $this->configConfigSourceWebsiteFactory = $configConfigSourceWebsiteFactory;
        $this->directoryConfigSourceCountryFactory = $directoryConfigSourceCountryFactory;
        $this->backendSession = $backendSession;
        $this->registry = $registry;
		parent::__construct();
		$request = $this->getRequest();
		if (!$request->getParam("method")) {
				throw new \Exception("Method Undefined");
		}
	}

	protected function _prepareForm()
	{
		$form = $this->formFactory->create();
		$this->setForm($form);
		$fieldset = $form->addFieldset('shippingrate_form', array('legend' => __('Fee')));

		$fieldsetDimensions = $form->addFieldset('shippingrate_form_dim', array('legend' => __('Package Attributes')));

		$fieldsetZone = $form->addFieldset('shippingrate_form_zone', array('legend' => __('Country/Zone')));
		
		// Website
		if (!Mage::app()->isSingleStoreMode()) {
			$websites = array_merge(
										array(
											0 => __("- All -")),
											$this->configConfigSourceWebsiteFactory->create()->toOptionArray()
										);
			$fieldset->addField('website_id', 'select', array(
					'label' => __('Website'),
					'class' => 'required-entry',
					'required' => true,
					'name' => 'website_id',
					'values' => $websites
			));
		}
		
		$fieldset->addField('shipping_fee', 'text', array(
			'label' => __('Shipping Fee'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'shipping_fee',
			'after_element_html' => '<small>' . __('Amount must be entered in the default currency of the selected website.') . '</small>',
		));
			

		$fieldset->addField('method', 'hidden', array(
			'name' => 'method'
		));

		$fieldsetDimensions->addField('max_weight', 'text', array(
			'label' => __('Max Weight'),
			'name' => 'max_weight',
		));

		$fieldsetDimensions->addField('max_width', 'text', array(
			'label' => __('Max Width'),
			'name' => 'max_width',
		));

		$fieldsetDimensions->addField('max_height', 'text', array(
			'label' => __('Max Height'),
			'name' => 'max_height',
		));

		$fieldsetDimensions->addField('max_depth', 'text', array(
			'label' => __('Max Depth'),
			'name' => 'max_depth',
		));

		$fieldsetZone->addField('countries', 'multiselect', array(
			'name'  => 'country[]',
			'label'     => __('Country'),
			'after_element_html' => '<small>' . __('Select the countries where the shipping price is applicabel.') . '</small>',
			'values'    => $this->directoryConfigSourceCountryFactory->create()->toOptionArray(),
		));

		$fieldsetZone->addField('zipcode_range', 'text', array(
			'label' => __('Zip Codes'),
			'after_element_html' => '<small>' . __('Enter range as 20000-30000. Enter multiple ranges separated by a comma.') . '</small>',
			'required' => false,
			'name' => 'zipcode_range',
		));


		if ($this->backendSession->getShippingrateData()) {
			$values = $this->backendSession->getShippingrateData();
			$values['method'] = $this->getRequest()->getParam("method");
			$form->setValues($values);
			$this->backendSession->setShippingrateData(null);
		} elseif ($this->registry->registry('shippingrate_data')) {
			$values = $this->registry->registry('shippingrate_data')->getData();
			$values['method'] = $this->getRequest()->getParam("method");
			$form->setValues($values);
		}
		return parent::_prepareForm();
	}

	public function getUrl($route = '', $params = array())
	{
			$request = $this->getRequest();
			$params['method'] = $request->getParam("method");
			return parent::getUrl($route, $params);
	}

}