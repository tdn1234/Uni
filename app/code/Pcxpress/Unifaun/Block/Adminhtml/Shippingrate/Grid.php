<?php

namespace Pcxpress\Unifaun\Block\Adminhtml\Shippingrate;

use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Setup\Exception;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;
use Magento\Framework\Session\SessionManagerInterface;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Pcxpress\Unifaun\Model\shippingrateFactory
     */
    protected $_shippingrateFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Status
     */
    protected $_status;

    /** @var ShippingmethodFactory $shippingMethodFactory */
    protected $shippingMethodFactory;

    /** @var SessionManagerInterface */
    protected $sessionManager;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Pcxpress\Unifaun\Model\shippingrateFactory $shippingrateFactory
     * @param \Pcxpress\Unifaun\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Pcxpress\Unifaun\Model\ShippingrateFactory $ShippingrateFactory,
        \Pcxpress\Unifaun\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = [],
        ShippingmethodFactory $shippingmethodFactory,
        SessionManagerInterface $sessionManager
    )
    {
        $this->sessionManager = $sessionManager;

        $this->shippingMethodFactory = $shippingmethodFactory;
        $this->_shippingrateFactory = $ShippingrateFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
//        var_dump($this->_removeButton('add'));die;
//        $this->
//        var_dump($this->getUrl());
//        foreach(get_class_methods($this) as $a) {
//            var_dump($a);
//        }
//        die;
        $this->setId('postGrid');
        $this->setDefaultSort('shippingrate_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');

//        $this->buttonList->remove('add');
//        parent::_construct();
//        $this->removeButton('add');
        //Remove original add button
//        $this->_removeButton('add');

//        $this->buttonList->add(
//            '<your name>',
//            [
//                'label' => __('<your label>'),
//                'class' => 'save',
//                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/<youraction>') . '\')',
//                'style' => '    background-color: #ba4000; border-color: #b84002; box-shadow: 0 0 0 1px #007bdb;color: #fff;text-decoration: none;'
//            ]
//        );

    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
//        var_dump($this->sessionManager->getShippingmethodId());die;
        $shippingmethod_id = $this->getRequest()->getParam('shippingmethod_id');
        if (!$shippingmethod_id) {
            throw new Exception('Shipping method not found');
        }
        $this->sessionManager->setShippingmethodId($shippingmethod_id);
        $collection = $this->_shippingrateFactory->create()->getCollection();
        $collection->addFieldToFilter('shippingmethod_id', $shippingmethod_id);
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'shippingrate_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'shippingrate_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );


        $this->addColumn(
            'shippingmethod_id',
            [
                'header' => __('shippingmethod'),
                'index' => 'shippingmethod_id',
                'renderer' => 'Pcxpress\Unifaun\Block\Adminhtml\Shippingrate\Grid\Renderer\ShippingmethodName'
            ]
        );


        $this->addColumn(
            'max_weight',
            [
                'header' => __('max weight'),
                'index' => 'max_weight',
            ]
        );

        $this->addColumn(
            'max_width',
            [
                'header' => __('max width'),
                'index' => 'max_width',
            ]
        );

        $this->addColumn(
            'max_height',
            [
                'header' => __('max height'),
                'index' => 'max_height',
            ]
        );

        $this->addColumn(
            'max_depth',
            [
                'header' => __('max depth'),
                'index' => 'max_depth',
            ]
        );

        $this->addColumn(
            'shipping_fee',
            [
                'header' => __('shipping fee'),
                'index' => 'shipping_fee',
            ]
        );

        $this->addColumn(
            'zipcode_range',
            [
                'header' => __('zipcode range'),
                'index' => 'zipcode_range',
            ]
        );

        $this->addColumn(
            'countries',
            [
                'header' => __('countries'),
                'index' => 'countries',
            ]
        );

        $this->addColumn(
            'website_id',
            [
                'header' => __('website_id'),
                'index' => 'website_id',
            ]
        );


        //$this->addColumn(
        //'edit',
        //[
        //'header' => __('Edit'),
        //'type' => 'action',
        //'getter' => 'getId',
        //'actions' => [
        //[
        //'caption' => __('Edit'),
        //'url' => [
        //'base' => '*/*/edit'
        //],
        //'field' => 'shippingrate_id'
        //]
        //],
        //'filter' => false,
        //'sortable' => false,
        //'index' => 'stores',
        //'header_css_class' => 'col-action',
        //'column_css_class' => 'col-action'
        //]
        //);


        $this->addExportType($this->getUrl('unifaun/*/exportCsv', ['_current' => true]), __('CSV'));
        $this->addExportType($this->getUrl('unifaun/*/exportExcel', ['_current' => true]), __('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }


    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('shippingrate_id');
        //$this->getMassactionBlock()->setTemplate('Pcxpress_Unifaun::shippingrate/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('shippingrate');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('unifaun/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('unifaun/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses
                    ]
                ]
            ]
        );


        return $this;
    }


    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('unifaun/*/index', ['_current' => true]);
    }

    /**
     * @param \Pcxpress\Unifaun\Model\shippingrate|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {

        return $this->getUrl(
            'unifaun/*/edit',
            ['shippingrate_id' => $row->getId()]
        );

    }


    static public function getOptionArray37(ShippingmethodFactory $shippingmethodFactory)
    {
        $shippingMethods = $shippingmethodFactory->create()->getCollection();
        $data_array = array();
        foreach ($shippingMethods as $shippingMethod) {
            $data_array[$shippingMethod->getId()] = $shippingMethod->getTitle();
        }
        return ($data_array);
    }

    static public function getOptionArray38(CountryFactory $countryFactory)
    {
        $countries = $countryFactory->create()->getCollection();

        $data_array = array('0' => '---Please Select---');
        foreach ($countries as $country) {
            $data_array[$country->getId()] = $country->getName();
        }

        return ($data_array);
    }

    static public function getOptionArray39(array $array)
    {
        $data_array = array('0' => '---All---');
        foreach ($array as $data) {
            $data_array[$data['value']] = $data['label'];
        }

        return ($data_array);
    }
}