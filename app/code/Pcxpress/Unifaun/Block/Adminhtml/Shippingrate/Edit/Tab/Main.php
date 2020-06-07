<?php

namespace Pcxpress\Unifaun\Block\Adminhtml\Shippingrate\Edit\Tab;

use Pcxpress\Unifaun\Model\ShippingmethodFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Directory\Model\CountryFactory;
use Magento\Config\Model\Config\Source\WebsiteFactory;

/**
 * Shippingrate edit form main tab
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

    /** @var ShippingmethodFactory $shippingMethodFactory */
    protected $shippingMethodFactory;

    /**
     * @var CountryFactory $countryFactory
     */
    protected $countryFactory;

    /** @var WebsiteFactory $websiteFactory */
    protected $websiteFactory;

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
        ShippingmethodFactory $shippingmethodFactory,
        CountryFactory $countryFactory,
        WebsiteFactory $websiteFactory
    )
    {
//        var_dump($websiteFactory->create()->toOptionArray());
//        var_dump(get_class_methods($websiteFactory->create()));
//        var_dump(get_class($websiteFactory));die;
        $this->websiteFactory = $websiteFactory;
        $this->countryFactory = $countryFactory;
        $this->shippingMethodFactory = $shippingmethodFactory;
        $this->_systemStore = $systemStore;
        $this->_status = $status;
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
        $model = $this->_coreRegistry->registry('shippingrate');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        if ($model->getId()) {
            $fieldset->addField('shippingrate_id', 'hidden', ['name' => 'shippingrate_id']);
        }

        $fieldset->addField(
            'website_id',
            'select',
            [
                'name' => 'website_id',
                'label' => __('website_id'),
                'title' => __('website_id'),
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingrate\Grid::getOptionArray39($this->websiteFactory->create()->toOptionArray()),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'shipping_fee',
            'text',
            [
                'name' => 'shipping_fee',
                'label' => __('shipping fee'),
                'title' => __('shipping fee'),

                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'shippingmethod_id',
            'select',
            [
                'label' => __('Shipping method'),
                'title' => __('Shipping method'),
                'name' => 'shippingmethod_id',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingrate\Grid::getOptionArray37($this->shippingMethodFactory),
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'max_weight',
            'text',
            [
                'name' => 'max_weight',
                'label' => __('max weight'),
                'title' => __('max weight'),

                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'max_width',
            'text',
            [
                'name' => 'max_width',
                'label' => __('max width'),
                'title' => __('max width'),

                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'max_height',
            'text',
            [
                'name' => 'max_height',
                'label' => __('max height'),
                'title' => __('max height'),

                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'max_depth',
            'text',
            [
                'name' => 'max_depth',
                'label' => __('max depth'),
                'title' => __('max depth'),

                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'zipcode_range',
            'text',
            [
                'name' => 'zipcode_range',
                'label' => __('zipcode range'),
                'title' => __('zipcode range'),

                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'countries',
            'select',
            [
                'name' => 'countries',
                'label' => __('countries'),
                'title' => __('countries'),
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingrate\Grid::getOptionArray38($this->countryFactory),
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
