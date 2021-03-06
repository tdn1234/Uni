<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\LabelQueue;

class Grid extends \Magento\Backend\Block\Widget\Grid {

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\LabelQueue\CollectionFactory
     */
    protected $unifaunMysql4LabelQueueCollectionFactory;

    /**
     * @var \Magento\Sales\Model\Order\ShipmentFactory
     */
    protected $salesOrderShipmentFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\Mysql4\LabelQueue\CollectionFactory $unifaunMysql4LabelQueueCollectionFactory,
        \Magento\Sales\Model\Order\ShipmentFactory $salesOrderShipmentFactory
    )
    {
        $this->unifaunMysql4LabelQueueCollectionFactory = $unifaunMysql4LabelQueueCollectionFactory;
        $this->salesOrderShipmentFactory = $salesOrderShipmentFactory;
        parent::__construct();
        $this->setId('unifaunGrid');
        $this->setDefaultSort('label_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->unifaunMysql4LabelQueueCollectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('label_id', array(
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'label_id',
        ));
        
        $this->addColumn('shipment_id', array(
            'header' => __('Shipment #'),
            'align' => 'left',
            'index' => 'shipment_id',
            'renderer' => 'Pcxpress_Unifaun_Block_Adminhtml_LabelQueue_Renderer_ShippingNr',
        ));

        $this->addColumn('status', array(
            'header' => __('Printed'),
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => __('Yes'),
                0 => __('No')
             ),
        ));

        $this->addColumn('created_at', array(
            'header' => __('Created'),
            'align' => 'left',
            'index' => 'created_at',
            'type' => 'datetime',
        ));
        
        $this->addColumn('printed_at', array(
            'header' => __('Printed'),
            'align' => 'left',
            'index' => 'printed_at',
            'type' => 'datetime',
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('label_id');
        $this->getMassactionBlock()->setFormFieldName('label_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        
        $this->getMassactionBlock()->addItem('print', array(
                'label'=> __('Print'),
                'url'  => $this->getUrl('*/unifaun_labelQueue/doMassPrint'),
        ));  
        
        $this->getMassactionBlock()->addItem('print_start', array(
                'label'=> __('Print (and select start label)'),
                'url'  => $this->getUrl('*/unifaun_labelQueue/massPrint'),
        ));    
            
        
        return $this;
    }

    public function getRowUrl($row)
    {
        return null;//$this->getUrl('*/*/print', array('id' => $row->getId()));
    }
    
    private function _getShipmentNumber($shipmentId){
        $shipment = $this->salesOrderShipmentFactory->create()->load($shipmentId);
    }

}