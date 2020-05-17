<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnifaunConsignment;

class Grid extends \Magento\Backend\Block\Widget\Grid {

	protected static $_multiplePrintting = 'print_shipping_types';

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory
     */
    protected $salesResourceModelOrderShipmentCollectionFactory;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $backendAuthSession;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $salesResourceModelOrderShipmentCollectionFactory,
        \Magento\Backend\Model\Auth\Session $backendAuthSession
    )
	{
        $this->salesResourceModelOrderShipmentCollectionFactory = $salesResourceModelOrderShipmentCollectionFactory;
        $this->backendAuthSession = $backendAuthSession;
		parent::__construct();
		$this->setId('unifaunGridconsignment');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	/**
	 * Retrieve collection class
	 *
	 * @return string
	 */
	protected function _getCollectionClass()
	{
		return 'sales/order_collection';
	}

	protected function _prepareCollection()
	{
		$tablePrefix = Mage::getConfig()->getTablePrefix();

		$collection =  $this->salesResourceModelOrderShipmentCollectionFactory->create(); /** @var $collection Mage_Sales_Model_Resource_Order_Shipment_Collection */
		$collection->getSelect()->joinLeft($tablePrefix.'sales_flat_order', 'main_table.order_id = '. $tablePrefix . 'sales_flat_order.entity_id', 
			array('shipping_method' => 'shipping_method', 'order_increment_id' => 'increment_id')
		);

		$collection->distinct(true);

		// $collection->getSelect()->group('main_table.entity_id');

		$collection->getSelect()->joinLeft($tablePrefix.'sales_flat_shipment_track', 'main_table.order_id = '. $tablePrefix . 'sales_flat_shipment_track.order_id', 
			array('title')
		);

		
		$collection->addAttributeToFilter('shipping_method', array('like' => 'unifaun%'));
		$collection->addAttributeToSort('created_at', 'DESC');

		$collection->addFilterToMap('shipping_address_id', 'main_table.shipping_address_id');
		$collection->addFilterToMap('created_at', 'main_table.created_at');

		$collection->addFilterToMap('increment_id', 'main_table.increment_id');
		$collection->addFilterToMap('entity_id', 'main_table.entity_id');

		$collection->addFilterToMap('order_increment_id', 'sales_flat_order.increment_id');

		
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{

		$this->addColumn('entity_id', array(
			'header'=> Mage::helper('core')->__('Shipment #'),
			'width' => '80px',
			'type'  => 'text',
			'index' => 'entity_id',
		));

		$this->addColumn('increment_id', array(
			'header'    => Mage::helper('core')->__('Order #'),
			'width'     => '80px',
			'type'      => 'text',
			'index'     => 'order_increment_id',
			// 'renderer'  => 'Pcxpress_Unifaun_Block_Adminhtml_UnifaunConsignment_Renderer_OrderIncrementId',		
		));

		$this->addColumn('shipping_name', array(
			'header'    => Mage::helper('core')->__('Ship to Name'),
			'index'     => 'shipping_address_id',
			'renderer'  => 'Pcxpress_Unifaun_Block_Adminhtml_UnifaunConsignment_Renderer_BillingAddress',
			'width'     => '200px'
		));

		$this->addColumn('title', array(
			'header'    => Mage::helper('core')->__('Shipments'),
			'index'     => 'title',
			'renderer'  => 'Pcxpress_Unifaun_Block_Adminhtml_UnifaunConsignment_Renderer_Shipments',
		));

		$this->addColumn('created_at', array(
			'header' => Mage::helper('core')->__('Delivery date'),
			'index' => 'created_at',
			'type' => 'datetime',
			'width' => '100px',
		));

		$this->addColumn('is_complete', array(
			'header' => Mage::helper('core')->__('Validated'),
			'index' => 'is_complete',
			'renderer' => 'Pcxpress_Unifaun_Block_Adminhtml_UnifaunConsignment_Renderer_IsComplete',
			'width' => '70px',
		));

		$this->addColumn('pcxpress_status', array(
			'header' => Mage::helper('core')->__('Status'),
			'index' => 'pcxpress_status',
			'renderer' => 'Pcxpress_Unifaun_Block_Adminhtml_UnifaunConsignment_Renderer_Status',
			'width' => '70px',
		));

		$this->addColumn('consignment_label', array(
			'header' => Mage::helper('core')->__('Email Label'),
			'index' => 'consignment_label',
			'renderer' => 'Pcxpress_Unifaun_Block_Adminhtml_UnifaunConsignment_Renderer_EmailLabel',
			'width' => '70px',
		));

		if ($this->backendAuthSession->isAllowed('sales/order/actions/view')) {
			$this->addColumn('action', array(
				'header' => Mage::helper('core')->__('Action'),
				'width' => '100',
				'type' => 'action',
				'getter' => 'getOrderId',
				'actions' =>array(
					array(
						'caption' => Mage::helper('core')->__('View order'),
						'url'     => array('base'=>'adminhtml/sales_order/view'),
						'field'   => 'order_id'
					),
					
				),
				'filter' => false,
					'sortable' => false,
					'index' => 'stores',
					'is_system' => true,
				)
			);
		}
		return parent::_prepareColumns();
	}

	protected function _prepareMassaction()
	{
		// $collection = $this->getCollection();
		// echo $collection->getSelect()->__toString();
			$this->setMassactionIdField('entity_id');
			$this->getMassactionBlock()->setFormFieldName('shipment_ids');
			$this->getMassactionBlock()->setUseSelectAll(false);


			$this->getMassactionBlock()->addItem('print_shipping_label', array(
					'label'=> Mage::helper('core')->__('Unifaun: Print Shipping Labels'),
					'url'  => $this->getUrl('*/*/massPrint', array('type' => \Pcxpress\Unifaun\Helper\Consignment::LABEL_TYPE_ID)),
			));

			$this->getMassactionBlock()->addItem('print_shipping_receipt', array(
					'label'=> Mage::helper('core')->__('Unifaun: Print Shipping Receipts'),
					'url'  => $this->getUrl('*/*/massPrint', array('type' => \Pcxpress\Unifaun\Helper\Consignment::RECEIPT_TYPE_ID)),
			));

			$this->getMassactionBlock()->addItem(self::$_multiplePrintting, array(
					'label'=> Mage::helper('core')->__('Unifaun: Print Shipping Receipts & Labels')
					
			));

			return $this;
	}

	public function getRowUrl($row)
	{
			return false;
	}

	public function getGridUrl()
	{
			return $this->getUrl('*/*', array('_current'=>true));
	}

}