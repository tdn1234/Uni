<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnificationMatrix;

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
        $this->setDefaultSort('unification_priority');
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
        $this->addColumn('unification_id', array(
            'header' => Mage::helper('core')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => \Pcxpress\Unifaun\Helper\Data::SHIPPINGMETHOD_ID,
        ));

        $this->addColumn('active', array(
            'header' => Mage::helper('core')->__('Active'),
            'align' => 'left',
            'index' => 'active',
            'type' => 'options',
            'options' => array(
                1 => Mage::helper('core')->__('Yes'),
                0 => Mage::helper('core')->__('No')
            ),
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('core')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('unification_enable', array(
            'header' => Mage::helper('core')->__('Unification'),
            'align' => 'left',
            'index' => 'unification_enable',
            'type' => 'options',
            'options' => array(
                1 => Mage::helper('core')->__('Yes'),
                0 => Mage::helper('core')->__('No')
            ),
        ));

        $this->addColumn('unification_priority', array(
            'header' => Mage::helper('core')->__('Unification Priority'),
            'align' => 'left',
            'index' => 'unification_priority',
            'renderer'  => 'Pcxpress_Unifaun_Block_Adminhtml_UnificationMatrix_Renderer_Priority',
        ));

        $this->addColumn('unification_product_id', array(
            'header' => Mage::helper('core')->__('Unifiation Product'),
            'align' => 'left',
            'index' => 'unification_product_id',
            'renderer'  => 'Pcxpress_Unifaun_Block_Adminhtml_UnificationMatrix_Renderer_Product',
        ));

        $this->addColumn('multiple_parcels', array(
            'header' => Mage::helper('core')->__('Multiple Parcels Allowed'),
            'align' => 'left',
            'index' => 'multiple_parcels',
            'type' => 'options',
            'options' => array(
                1 => Mage::helper('core')->__('Yes'),
                0 => Mage::helper('core')->__('No')
            ),
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('unifaun/adminhtml_shippingMethod/edit', array('id' => $row->getId()));
    }

}