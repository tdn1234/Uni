<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingMethod\Edit\Tab;


/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
class Form extends \Magento\Backend\Block\Widget\Form
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $storeSystemStore;

    /**
     * @var \Pcxpress\Unifaun\Model\SourceModel\AdviceTypesFactory
     */
    protected $unifaunSourceModelAdviceTypesFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\SourceModel\ShippingMethodsFactory
     */
    protected $unifaunSourceModelShippingMethodsFactory;

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
        \Magento\Store\Model\System\Store $storeSystemStore,
        \Pcxpress\Unifaun\Model\SourceModel\AdviceTypesFactory $unifaunSourceModelAdviceTypesFactory,
        \Pcxpress\Unifaun\Model\SourceModel\ShippingMethodsFactory $unifaunSourceModelShippingMethodsFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory
    ) {
        $this->formFactory = $formFactory;
        $this->storeSystemStore = $storeSystemStore;
        $this->unifaunSourceModelAdviceTypesFactory = $unifaunSourceModelAdviceTypesFactory;
        $this->unifaunSourceModelShippingMethodsFactory = $unifaunSourceModelShippingMethodsFactory;
        $this->backendSession = $backendSession;
        $this->registry = $registry;
    }
    /**
	 * _prepareForm
	 * @param null	
	 * @return object
	 */
	protected function _prepareForm()
	{
		$form = $this->formFactory->create();
		$this->setForm($form);
		$fieldset = $form->addFieldset('shippingmethod_form', array('legend' => __('Details')));
		$fieldsetConsignment = $form->addFieldset('shippingmethod_consignment_form', array('legend' => __('Consignment Weight')));
		$fieldsetAdv = $form->addFieldset('shippingmethod_advanced_form', array('legend' => __('Advanced')));
		$fieldsetUnification = $form->addFieldset('shippingmethod_unification_form', array('legend' => __('Unification of package configuration')));
		
		

		$fieldset->addField('title', 'text', array(
			'label' => __('Title'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'title',
		));

		$fieldset->addField('template_name', 'text', array(
			'label' => __('Template Name'),
			'required' => false,
			'name' => 'template_name',
		));

		$fieldset->addField('shipping_service', 'select', array(
			'label' => __('Shipping Service'),
			'required' => true,
			'name' => 'shipping_service',
			'values' => array(
					array(),
					array(
						'value' => \Pcxpress\Unifaun\Helper\Data::WEBTA_SHIPPING_ID,
						'label' => __('Web TA'),
					),
					array(
						'value' => \Pcxpress\Unifaun\Helper\Data::PACTSOFT_SHIPPING_ID,
						'label' => __('Pacsoft'),
					),
				),
		));

		$fieldset->addField('description', 'editor', array(
			'name' => 'description',
			'label' => __('Description'),
			'title' => __('Description'),
			'style' => 'width:700px; height:200px;',
			'wysiwyg' => false,
			'required' => false,
		));

		if (!Mage::app()->isSingleStoreMode()) {
			$fieldset->addField('store_ids', 'multiselect', array(
				'name'      => 'store_ids[]',
				'label'     => Mage::helper('cms')->__('Store View'),
				'title'     => Mage::helper('cms')->__('Store View'),
				'required'  => true,
				'values'    => $this->storeSystemStore->getStoreValuesForForm(false, true),
				'after_element_html'   => '<br /><small>' . __('Availble for only selected store views.') . '</small>'
			));
		}

		$fieldsetAdv->addField('label_only', 'select', array(
				'label' => __('Label Only'),
				'name' => 'label_only',
				'values' => array(
					array(
						'value' => 1,
						'label' => __('Yes'),
					),
					array(
						'value' => 0,
						'label' => __('No'),
					),
				),
		));

		$fieldsetAdv->addField('frontend_visibility', 'select', array(
				'label' => __('Visible at Frontend'),
				'name' => 'frontend_visibility',
				'values' => array(
						array(
								'value' => 1,
								'label' => __('Yes'),
						),
						array(
								'value' => 0,
								'label' => __('No'),
						),
				),
		));

		$fieldsetAdv->addField('no_booking', 'select', array(
				'label' => __('No booking'),
				'name' => 'no_booking',
				'values' => array(
						array(
								'value' => 1,
								'label' => __('Yes'),
						),
						array(
								'value' => 0,
								'label' => __('No'),
						),
				),
		));

		$fieldsetAdv->addField('active', 'select', array(
				'label' => __('Active'),
				'name' => 'active',
				'values' => array(
						array(
								'value' => 1,
								'label' => __('Yes'),
						),
						array(
								'value' => 0,
								'label' => __('No'),
						),
				),
		));

		$fieldsetAdv->addField('free_shipping_enable', 'select', array(
				'label' => __('Free Shipping Enabled'),
				'name' => 'free_shipping_enable',
				'values' => array(
						array(
								'value' => 1,
								'label' => __('Enabled'),
						),
						array(
								'value' => 0,
								'label' => __('Disabled'),
						),
				),
		));

		$fieldsetAdv->addField('free_shipping_subtotal', 'text', array(
				'label' => __('Free Shipping Subtotal'),
				'class' => '',
				'required' => false,
				'name' => 'free_shipping_subtotal',
		));

		$fieldsetAdv->addField('handling_fee', 'text', array(
				'label' => __('Handling Fee'),
				'class' => '',
				'required' => false,
				'name' => 'handling_fee',
		));

		$fieldsetAdv->addField('last_booking_time', 'text', array(
				'label' => __('Last Booking Time (hh:mm)'),
				'class' => '',
				'required' => false,
				'name' => 'last_booking_time',
		));

		$fieldsetAdv->addField('shipping_group', 'text', array(
				'label' => __('Shipping Group'),
				'class' => '',
				'required' => true,
				'name' => 'shipping_group'
		));

		$fieldsetAdv->addField('multiple_parcels', 'select', array(
				'label' => __('Multiple Parcels Allowed'),
				'name' => 'multiple_parcels',
				'after_element_html' => '<small><br>' . __('If you are selecting yes make sure that shipping method must support this feature.') . '</small>',
				'values' => array(
						array(
								'value' => 1,
								'label' => __('Yes'),
						),
						array(
								'value' => 0,
								'label' => __('No'),
						),
				),
		));

		$fieldsetConsignment->addField('min_consignment_weight', 'text', array(
				'label' => __('Min'),
				'class' => '',
				'required' => false,
				'name' => 'min_consignment_weight',
		));

		$fieldsetConsignment->addField('max_consignment_weight', 'text', array(
				'label' => __('Max'),
				'class' => '',
				'required' => false,
				'name' => 'max_consignment_weight',
		));

		$fieldsetUnification->addField('insurance_enable', 'select', array(
				'label' => __('Insurance'),
				'name' => 'insurance_enable',
				'values' => array(
						array(
								'value' => 1,
								'label' => Mage::helper('core')->__('Yes'),
						),
						array(
								'value' => 0,
								'label' => Mage::helper('core')->__('No'),
						),
				),
		));

		$fieldsetUnification->addField('unification_enable', 'select', array(
				'label' => __('Unification'),
				'name' => 'unification_enable',
				'values' => array(
						array(
								'value' => 1,
								'label' => __('Yes'),
						),
						array(
								'value' => 0,
								'label' => __('No'),
						),
				),
		));
			
		$fieldsetUnification->addField('advice_default', 'select', array(
				'label' => __('Default advice'),
				'name' => 'advice_default',
				'after_element_html' => '<small>' . __('Default advice for unification or batch process.') . '</small>',
				'values' => $this->unifaunSourceModelAdviceTypesFactory->create()->toOptionArray()
		));

		$fieldsetUnification->addField('unification_product_id', 'select', array(
				'label' => __('Unification Product'),
				'name' => 'unification_product_id',
				'after_element_html' => '<small><br>' . __('In case of unified shipments use this shipping method.') . '</small>',
				'values' => $this->unifaunSourceModelShippingMethodsFactory->create()->toOptionArray()
		));

		$fieldsetUnification->addField('unification_priority', 'text', array(
				'label' => __('Unification Priority'),
				'after_element_html' => '<small>' . __('Shipping method with the lowest priority is selected.') . '</small>',
				'class' => '',
				'name' => 'unification_priority'
		));

		if ($this->backendSession->getWebData()) {
				$form->setValues($this->backendSession->getShippingmethodData());
				$this->backendSession->setWebData(null);
		} elseif ($this->registry->registry('shippingmethod_data')) {
				$data = $this->registry->registry('shippingmethod_data')->getData();
				if (!array_key_exists("shipping_group", $data)) {
						$data['frontend_visibility'] = 1;
						$data['active'] = 1;
						$data['shipping_group'] = 1;
						$data['min_consignment_weight'] = 0;
						$data['max_consignment_weight'] = 100000;
				}		

				$form->setValues($data);
		} else {
				$form->setValues(array(
						'min_weight' => 0,
						'min_weight' => 100000
				));
		}
		return parent::_prepareForm();
	}
}