<?php

namespace Pcxpress\Unifaun\Block\Adminhtml\Pickuplocation\Edit\Tab;

/**
 * Pickuplocation edit form main tab
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
        array $data = []
    ) {
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
        $model = $this->_coreRegistry->registry('pickuplocation');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        if ($model->getId()) {
            $fieldset->addField('pickuplocation_id', 'hidden', ['name' => 'pickuplocation_id']);
        }

		
        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'address',
            'text',
            [
                'name' => 'address',
                'label' => __('Address'),
                'title' => __('Address'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'postcode',
            'text',
            [
                'name' => 'postcode',
                'label' => __('Postcode'),
                'title' => __('Postcode'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'state',
            'text',
            [
                'name' => 'state',
                'label' => __('State'),
                'title' => __('State'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'countrycode',
            'text',
            [
                'name' => 'countrycode',
                'label' => __('Countrycode'),
                'title' => __('Countrycode'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'contact_person',
            'text',
            [
                'name' => 'contact_person',
                'label' => __('contact person'),
                'title' => __('contact person'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'phone',
            'text',
            [
                'name' => 'phone',
                'label' => __('phone'),
                'title' => __('phone'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'mobile',
            'text',
            [
                'name' => 'mobile',
                'label' => __('mobile'),
                'title' => __('mobile'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'fax',
            'text',
            [
                'name' => 'fax',
                'label' => __('fax'),
                'title' => __('fax'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('email'),
                'title' => __('email'),
				
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

    public function getTargetOptionArray(){
    	return array(
    				'_self' => "Self",
					'_blank' => "New Page",
    				);
    }
}
