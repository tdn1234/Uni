<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingPrice\Edit\Tab;

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
            throw new \Exception("Missing Method");
        }
    }

    protected function _prepareForm()
    {
        $form = $this->formFactory->create();
        $this->setForm($form);
        $fieldset = $form->addFieldset('shippingprice_form', array('legend' => __('Details')));

        $fieldsetDimensions = $form->addFieldset('shippingprice_form_dim', array('legend' => __('Weight and Dimensions')));

        $fieldsetZone = $form->addFieldset('shippingprice_form_zone', array('legend' => __('Zone')));

        // Website
        if (!Mage::app()->isSingleStoreMode()) {
            $websites = array_merge(array(0 => __("- All -")), $this->configConfigSourceWebsiteFactory->create()->toOptionArray());
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
            'after_element_html' => '<small>' . __('Must be entered in the currency set as default currency on the selected website. If "All" is selected, the price will be in the current websites currency.') . '</small>',
        ));
        

        $fieldset->addField('method', 'hidden', array(
            'name' => 'method'
        ));

        $fieldsetDimensions->addField('weight_max', 'text', array(
            'label' => __('Max Weight'),
            'name' => 'weight_max',
        ));

        $fieldsetDimensions->addField('width_max', 'text', array(
            'label' => __('Max Width'),
            'name' => 'width_max',
        ));

        $fieldsetDimensions->addField('height_max', 'text', array(
            'label' => __('Max Height'),
            'name' => 'height_max',
        ));

        $fieldsetDimensions->addField('depth_max', 'text', array(
            'label' => __('Max Depth'),
            'name' => 'depth_max',
        ));


        $fieldsetZone->addField('countries', 'multiselect', array(
            'name'  => 'country[]',
            'label'     => __('Country'),
            'after_element_html' => '<small>' . __('Select the countries where this shipping price should be available.') . '</small>',
            'values'    => $this->directoryConfigSourceCountryFactory->create()->toOptionArray(),
        ));

        $fieldsetZone->addField('zipcode_ranges', 'text', array(
            'label' => __('Zip Codes'),
            'after_element_html' => '<small>' . __('Enter range as 10000-40000. Multiple ranges can be separated by a comma.') . '</small>',
            'required' => false,
            'name' => 'zipcode_ranges',
        ));


        if ($this->backendSession->getShippingpriceData()) {
            $values = $this->backendSession->getShippingpriceData();
            $values['method'] = $this->getRequest()->getParam("method");
            $form->setValues($values);
            $this->backendSession->setShippingpriceData(null);
        } elseif ($this->registry->registry('shippingprice_data')) {
            $values = $this->registry->registry('shippingprice_data')->getData();
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