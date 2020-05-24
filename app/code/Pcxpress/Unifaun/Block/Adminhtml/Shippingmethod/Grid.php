<?php

namespace Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Pcxpress\Unifaun\Model\shippingmethodFactory
     */
    protected $_shippingmethodFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Pcxpress\Unifaun\Model\shippingmethodFactory $shippingmethodFactory
     * @param \Pcxpress\Unifaun\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Pcxpress\Unifaun\Model\ShippingmethodFactory $ShippingmethodFactory,
        \Pcxpress\Unifaun\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    )
    {
        $this->_shippingmethodFactory = $ShippingmethodFactory;
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
        $this->setId('postGrid');
        $this->setDefaultSort('shippingmethod_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_shippingmethodFactory->create()->getCollection();
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
            'shippingmethod_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'shippingmethod_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );


        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
            ]
        );

        $this->addColumn(
            'template_name',
            [
                'header' => __('Template name'),
                'index' => 'template_name',
            ]
        );

        $this->addColumn(
            'shipping_service',
            [
                'header' => __('Shipping service'),
                'index' => 'shipping_service',
            ]
        );

        $this->addColumn(
            'min_consignment_weight',
            [
                'header' => __('Consignment weight min'),
                'index' => 'min_consignment_weight',
            ]
        );

        $this->addColumn(
            'max_consignment_weight',
            [
                'header' => __('Consignment weight max'),
                'index' => 'max_consignment_weight',
            ]
        );


        $this->addColumn(
            'label_only',
            [
                'header' => __('Label only'),
                'index' => 'label_only',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray7()
            ]
        );


        $this->addColumn(
            'frontend_visibility',
            [
                'header' => __('Visible at Frontend'),
                'index' => 'frontend_visibility',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray8()
            ]
        );


        $this->addColumn(
            'no_booking',
            [
                'header' => __('No booking'),
                'index' => 'no_booking',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray9()
            ]
        );


        $this->addColumn(
            'active',
            [
                'header' => __('Active'),
                'index' => 'active',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray10()
            ]
        );


        $this->addColumn(
            'free_shipping_enable',
            [
                'header' => __('Free Shipping Enabled'),
                'index' => 'free_shipping_enable',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray11()
            ]
        );


        $this->addColumn(
            'free_shipping_subtotal',
            [
                'header' => __('Free Shipping Subtotal'),
                'index' => 'free_shipping_subtotal',
            ]
        );

        $this->addColumn(
            'handling_fee',
            [
                'header' => __('Handling Fee'),
                'index' => 'handling_fee',
            ]
        );

        $this->addColumn(
            'last_booking_time',
            [
                'header' => __('Last Booking Time (hh:mm)'),
                'index' => 'last_booking_time',
                'type' => 'datetime',
            ]
        );


        $this->addColumn(
            'shipping_group',
            [
                'header' => __('Shipping group'),
                'index' => 'shipping_group',
            ]
        );


        $this->addColumn(
            'multiple_parcels',
            [
                'header' => __('Multiple Parcels Allowed'),
                'index' => 'multiple_parcels',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray16()
            ]
        );


        $this->addColumn(
            'insurance_enable',
            [
                'header' => __('Insurance'),
                'index' => 'insurance_enable',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray17()
            ]
        );


        $this->addColumn(
            'unification_enable',
            [
                'header' => __('Unification'),
                'index' => 'unification_enable',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray18()
            ]
        );


        $this->addColumn(
            'advice_default',
            [
                'header' => __('Default advice'),
                'index' => 'advice_default',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray19()
            ]
        );


        $this->addColumn(
            'unification_product_id',
            [
                'header' => __('Unification Product'),
                'index' => 'unification_product_id',
                'type' => 'options',
                'options' => \Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray20()
            ]
        );


        $this->addColumn(
            'unification_priority',
            [
                'header' => __('Unification Priority'),
                'index' => 'unification_priority',
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
        //'field' => 'shippingmethod_id'
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

        $this->setMassactionIdField('shippingmethod_id');
        //$this->getMassactionBlock()->setTemplate('Pcxpress_Unifaun::shippingmethod/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('shippingmethod');

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
     * @param \Pcxpress\Unifaun\Model\shippingmethod|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {

        return $this->getUrl(
            'unifaun/*/edit',
            ['shippingmethod_id' => $row->getId()]
        );

    }


    static public function getOptionArray4()
    {
        $data_array = array();
        $data_array[0] = 'Store';
        return ($data_array);
    }

    static public function getValueArray4()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray4() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray7()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray7()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray7() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray8()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray8()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray8() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray9()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray9()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray9() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray10()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray10()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray10() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray11()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray11()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray11() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray16()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray16()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray16() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray17()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray17()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray17() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray18()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray18()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray18() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray19()
    {
        $data_array = array();
        $data_array[0] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray19()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray19() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

    static public function getOptionArray20()
    {

        $data_array = array();
        $data_array[0] = 'Yes';

        return ($data_array);
    }

    static public function getValueArray20()
    {
        $data_array = array();
        foreach (\Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid::getOptionArray20() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
//        var_dump($data_array);
        return ($data_array);

    }


}