<?php

namespace Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Edit\Tab;

use Pcxpress\Unifaun\Helper\Data;
use Magento\Store\Model\System\Store;
/**
 * Shippingmethod edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Pcxpress\Unifaun\Model\Status
     */
    protected $_status;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $helper;

    protected $store;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Pcxpress\Unifaun\Model\Status $status,
        array $data = [],
        Data $helper,
        Store $store
    )
    {
        $this->store = $store;
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        $this->helper = $helper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Pcxpress\Unifaun\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('shippingmethod');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        if ($model->getId()) {
            $fieldset->addField('shippingmethod_id', 'hidden', ['name' => 'shippingmethod_id']);
        }


        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'template_name',
            'text',
            [
                'name' => 'template_name',
                'label' => __('Template name'),
                'title' => __('Template name'),

                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'shipping_service',
            'text',
            [
                'name' => 'shipping_service',
                'label' => __('Shipping service'),
                'title' => __('Shipping service'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),

                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'store_ids',
            'multiselect',
            [
                'label' => __('Store view'),
                'title' => __('Store view'),
                'name' => 'store_ids[]',
                'values' => $this->store->getStoreValuesForForm(false, true),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'min_consignment_weight',
            'text',
            [
                'name' => 'min_consignment_weight',
                'label' => __('Consignment weight min'),
                'title' => __('Consignment weight min'),

                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'max_consignment_weight',
            'text',
            [
                'name' => 'max_consignment_weight',
                'label' => __('Consignment weight max'),
                'title' => __('Consignment weight max'),

                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'label_only',
            'select',
            [
                'label' => __('Label only'),
                'title' => __('Label only'),
                'name' => 'label_only',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'frontend_visibility',
            'select',
            [
                'label' => __('Visible at Frontend'),
                'title' => __('Visible at Frontend'),
                'name' => 'frontend_visibility',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'no_booking',
            'select',
            [
                'label' => __('No booking'),
                'title' => __('No booking'),
                'name' => 'no_booking',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'active',
            'select',
            [
                'label' => __('Active'),
                'title' => __('Active'),
                'name' => 'active',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'free_shipping_enable',
            'select',
            [
                'label' => __('Free Shipping Enabled'),
                'title' => __('Free Shipping Enabled'),
                'name' => 'free_shipping_enable',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'free_shipping_subtotal',
            'text',
            [
                'name' => 'free_shipping_subtotal',
                'label' => __('Free Shipping Subtotal'),
                'title' => __('Free Shipping Subtotal'),

                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'handling_fee',
            'text',
            [
                'name' => 'handling_fee',
                'label' => __('Handling Fee'),
                'title' => __('Handling Fee'),

                'disabled' => $isElementDisabled
            ]
        );


        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::MEDIUM
        );
        $timeFormat = $this->_localeDate->getTimeFormat(
            \IntlDateFormatter::MEDIUM
        );

        $fieldset->addField(
            'last_booking_time',
            'date',
            [
                'name' => 'last_booking_time',
                'label' => __('Last Booking Time (hh:mm)'),
                'title' => __('Last Booking Time (hh:mm)'),
                'date_format' => $dateFormat,
                //'time_format' => $timeFormat,

                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'shipping_group',
            'text',
            [
                'name' => 'shipping_group',
                'label' => __('Shipping group'),
                'title' => __('Shipping group'),

                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'multiple_parcels',
            'select',
            [
                'label' => __('Multiple Parcels Allowed'),
                'title' => __('Multiple Parcels Allowed'),
                'name' => 'multiple_parcels',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'insurance_enable',
            'select',
            [
                'label' => __('Insurance'),
                'title' => __('Insurance'),
                'name' => 'insurance_enable',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'unification_enable',
            'select',
            [
                'label' => __('Unification'),
                'title' => __('Unification'),
                'name' => 'unification_enable',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'advice_default',
            'select',
            [
                'label' => __('Default advice'),
                'title' => __('Default advice'),
                'name' => 'advice_default',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );

//        \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getValueArray20();

        $fieldset->addField(
            'unification_product_id',
            'select',
            [
                'label' => __('Unification Product'),
                'title' => __('Unification Product'),
                'name' => 'unification_product_id',

                'options' => $this->helper->getShippingMethods(),
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'unification_priority',
            'text',
            [
                'name' => 'unification_priority',
                'label' => __('Unification Priority'),
                'title' => __('Unification Priority'),

                'disabled' => $isElementDisabled
            ]
        );


        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Item Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    public function getTargetOptionArray()
    {
        return array(
            '_self' => "Self",
            '_blank' => "New Page",
        );
    }
}
