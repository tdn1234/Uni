<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnifaunMatrix;

class Grid extends \Magento\Backend\Block\Widget\Grid {

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory
     */
    protected $unifaunMysql4ShippingMethodCollectionFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory
    )
    {
        $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
        parent::__construct();
        $this->setId('unifaunGrid');
        $this->setDefaultSort('consolidation_priority');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->unifaunMysql4ShippingMethodCollectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('unifaun_id', array(
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'shippingmethod_id',
        ));

        $this->addColumn('activated', array(
            'header' => __('Activated'),
            'align' => 'left',
            'index' => 'activated',
            'type' => 'options',
            'options' => array(
                1 => __('Yes'),
                0 => __('No')
            ),
        ));

        $this->addColumn('title', array(
            'header' => __('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('consolidation_enable', array(
            'header' => __('Consolidation'),
            'align' => 'left',
            'index' => 'consolidation_enable',
            'type' => 'options',
            'options' => array(
                1 => __('Yes'),
                0 => __('No')
            ),
        ));

        $this->addColumn('consolidation_priority', array(
            'header' => __('Consolidation priority'),
            'align' => 'left',
            'index' => 'consolidation_priority',
            'renderer'  => 'Pcxpress_Unifaun_Block_Adminhtml_UnifaunMatrix_Renderer_ConsolidationPriority',
        ));

        $this->addColumn('consolidation_product_id', array(
            'header' => __('Consolidation product'),
            'align' => 'left',
            'index' => 'consolidation_product_id',
            'renderer'  => 'Pcxpress_Unifaun_Block_Adminhtml_UnifaunMatrix_Renderer_ConsolidationProduct',
        ));

        $this->addColumn('multiple_parcels', array(
            'header' => __('Allow multiple parcels'),
            'align' => 'left',
            'index' => 'multiple_parcels',
            'type' => 'options',
            'options' => array(
                1 => __('Yes'),
                0 => __('No')
            ),
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('admin/unifaun_shippingMethod/edit', array('id' => $row->getId()));
    }

}