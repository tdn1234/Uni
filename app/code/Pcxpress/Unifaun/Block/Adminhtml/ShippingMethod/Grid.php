<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingMethod;

class Grid extends \Magento\Backend\Block\Widget\Grid 
{

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory
     */
    protected $unifaunMysql4ShippingMethodCollectionFactory;

    /**
	 * Constructor
	 * @param null	
	 * @return null
	 */	
	public function __construct(
        \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory
    )
	{
        $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
		parent::__construct();
		$this->setId('unifaunGrid');
		$this->setDefaultSort('title');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	
	/**
	 * _prepareCollection
	 * @param null	
	 * @return object
	 */	
	protected function _prepareCollection()
	{
		$collection = $this->unifaunMysql4ShippingMethodCollectionFactory->create();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	/**
	 * _prepareColumns
	 * @param null	
	 * @return object
	 */	
	protected function _prepareColumns()
	{
		$this->addColumn(\Pcxpress\Unifaun\Helper\Data::SHIPPINGMETHOD_ID, array(
			'header' => __('ID'),
			'align' => 'right',
			'width' => '50px',
			'index' => \Pcxpress\Unifaun\Helper\Data::SHIPPINGMETHOD_ID,
		));

		$this->addColumn('title', array(
			'header' => __('Title'),
			'align' => 'left',
			'index' => 'title',
		));

		$this->addColumn('template_name', array(
			'header' => __('Template Name'),
			'align' => 'left',
			'index' => 'template_name',
		));

		$this->addColumn('shipping_group', array(
			'header' => __('Shipping Group'),
			'align' => 'left',
			'index' => 'shipping_group',
		));
		
		$this->addColumn('label_only', array(
			'header' => __('Label Only'),
			'align' => 'left',
			'index' => 'label_only',
			'type' => 'options',
			'options' => array(
					1 => __('Yes'),
					0 => __('No')
			 ),
		));
		
		$this->addColumn('frontend_visibility', array(
			'header' => __('Frontend Visibility'),
			'align' => 'left',
			'index' => 'frontend_visibility',
			'type' => 'options',
			'options' => array(
					1 => __('Yes'),
					0 => __('No')
			 ),
		));
		
		$this->addColumn('no_booking', array(
			'header' => __('No booking'),
			'align' => 'left',
			'index' => 'no_booking',
			'type' => 'options',
			'options' => array(
					1 => __('Yes'),
					0 => __('No')
			 ),
		));

		$this->addColumn('unification_enable', array(
			'header' => __('Unification'),
			'align' => 'left',
			'index' => 'unification_enable',
			'type' => 'options',
			'options' => array(
					1 => __('Yes'),
					0 => __('No')
			),
		));

		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_ids', array(
				'header' => __('Store Views'),
				'align' => 'left',
				'index' => 'store_ids',
				'type' => 'options',
				'renderer' => 'unifaun/adminhtml_shippingMethod_renderer_storeViews',
				'filter' => false,
				'sortable' => false,
			));
		}
		
		$this->addColumn('active', array(
			'header' => __('Active'),
			'align' => 'left',
			'index' => 'active',
			'type' => 'options',
			'options' => array(
				1 => __('Yes'),
				0 => __('No')
			),
		));

		$this->addColumn('rates', array(
			'header' => __('Rates'),
			'align' => 'left',
			'filter' => false,
			'renderer' => 'unifaun/adminhtml_shippingMethod_renderer_rateList',
		));

		$this->addColumn('action', array(
			'header' => __('Action'),
			'width' => '100',
			'type' => 'action',
			'getter' => 'getId',
			'actions' => array(
				array(
					'caption' => __('Manage Rates'),
					'url' => array('base' => 'unifaun/adminhtml_shippingRate/index'),
					'field' => 'method'
				),
				array(
					'caption' => __('Edit'),
					'url' => array('base' => '*/*/edit'),
					'field' => 'id'
				)
			),
			'filter' => false,
			'sortable' => false,
			'index' => 'stores',
			'is_system' => true,
		));

		return parent::_prepareColumns();
	}
	
	/**
	 * _prepareMassaction
	 * @param null	
	 * @return object
	 */	
	protected function _prepareMassaction()
	{
		return $this;
	}
	
	/**
	 * getRowUrl
	 * @param object	
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}