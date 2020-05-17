<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\PickupLocation\Edit\Tab;


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
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $formFactory;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory
    ) {
        $this->formFactory = $formFactory;
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
	
		$locationFieldset = $form->addFieldset('pickuplocation_form',
				array('legend' => __('Location')));
	
		$communicationFieldset = $form->addFieldset('pickupaddresscommunication_form',
				array('legend' => __('Communication')));
	
		$locationFieldset->addField('name', 'text', array(
				'label' => __('Name'),
				'class' => 'required-entry',
				'required' => true,
				'name' => 'name',
		));
		$locationFieldset->addField('address', 'textarea', array(
				'label' => __('Address'),
				'class' => 'required-entry',
				'required' => true,
				'name' => 'address',
		));
		$locationFieldset->addField('postcode', 'text', array(
				'label' => __('Postcode'),
				'class' => 'required-entry',
				'required' => true,
				'name' => 'postcode',
		));
		$locationFieldset->addField('city', 'text', array(
				'label' => __('City'),
				'class' => 'required-entry',
				'required' => true,
				'name' => 'city',
		));
		$locationFieldset->addField('state', 'text', array(
				'label' => __('State'),
				'name' => 'state',
		));
		$locationFieldset->addField('countrycode', 'text', array(
				'label' => __('Country code'),
				'class' => 'required-entry',
				'required' => true,
				'name' => 'countrycode',
		));
	
		$communicationFieldset->addField('contact_person', 'text', array(
				'label' => __('Contact person'),
				'name' => 'contact_person',
		));
		$communicationFieldset->addField('phone', 'text', array(
				'label' => __('Phone'),
				'name' => 'phone',
		));
		$communicationFieldset->addField('mobile', 'text', array(
				'label' => __('Mobile'),
				'name' => 'mobile',
		));
		$communicationFieldset->addField('fax', 'text', array(
				'label' => __('Fax'),
				'name' => 'fax',
		));
		$communicationFieldset->addField('email', 'text', array(
				'label' => __('Email'),
				'name' => 'email',
		));
	
		$form->setValues($this->registry->registry('pickuplocation_data'));
	
		return parent::_prepareForm();
	}
}