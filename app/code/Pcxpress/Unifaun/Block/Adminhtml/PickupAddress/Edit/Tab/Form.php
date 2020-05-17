<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\PickupAddress\Edit\Tab;


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
    protected function _prepareForm()
    {
        $form = $this->formFactory->create();

        $this->setForm($form);

        /* @var $addressFieldset Varien_Data_Form_Element_Fieldset */
        $addressFieldset = $form->addFieldset('pickupaddress_form',
            array('legend' => __('Address')));

        /* @var $communicationFieldset Varien_Data_Form_Element_Fieldset */
        $communicationFieldset = $form->addFieldset('pickupaddresscommunication_form',
            array('legend' => __('Communication')));

        $addressFieldset->addField('address_name', 'text', array(
            'label' => __('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'address_name',
        ));
        $addressFieldset->addField('address_address1', 'text', array(
            'label' => __('Address Line 1'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'address_address1',
        ));
        $addressFieldset->addField('address_address2', 'text', array(
            'label' => __('Address Line 2'),
            'name' => 'address_address2',
        ));
        $addressFieldset->addField('address_address3', 'text', array(
            'label' => __('Address Line 3'),
            'name' => 'address_address3',
        ));
        $addressFieldset->addField('address_postcode', 'text', array(
            'label' => __('Postcode'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'address_postcode',
        ));
        $addressFieldset->addField('address_city', 'text', array(
            'label' => __('City'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'address_city',
        ));
        $addressFieldset->addField('address_state', 'text', array(
            'label' => __('State'),
            'name' => 'address_state',
        ));
        $addressFieldset->addField('address_countrycode', 'text', array(
            'label' => __('Country code'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'address_countrycode',
        ));

        $communicationFieldset->addField('communication_contact_person', 'text', array(
            'label' => __('Contact person'),
            'name' => 'communication_contact_person',
        ));
        $communicationFieldset->addField('communication_phone', 'text', array(
            'label' => __('Phone'),
            'name' => 'communication_phone',
        ));
        $communicationFieldset->addField('communication_mobile', 'text', array(
            'label' => __('Mobile'),
            'name' => 'communication_mobile',
        ));
        $communicationFieldset->addField('communication_fax', 'text', array(
            'label' => __('Fax'),
            'name' => 'communication_fax',
        ));
        $communicationFieldset->addField('communication_email', 'text', array(
            'label' => __('Email'),
            'name' => 'communication_email',
        ));

        $form->setValues($this->registry->registry('pickupaddress_data'));

        return parent::_prepareForm();
    }
}