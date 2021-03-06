<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\Label;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Pcxpress\Unifaun\Model\labelFactory
     */
    protected $_labelFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Pcxpress\Unifaun\Model\labelFactory $labelFactory
     * @param \Pcxpress\Unifaun\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Pcxpress\Unifaun\Model\LabelFactory $LabelFactory,
        \Pcxpress\Unifaun\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_labelFactory = $LabelFactory;
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
        $this->setDefaultSort('label_id');
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
        $collection = $this->_labelFactory->create()->getCollection();
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
            'label_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'label_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );


		
				$this->addColumn(
					'shipment_id',
					[
						'header' => __('Shipment'),
						'index' => 'shipment_id',
					]
				);
				

						$this->addColumn(
							'status',
							[
								'header' => __('Status'),
								'index' => 'status',
								'type' => 'options',
								'options' => \Pcxpress\Unifaun\Block\Adminhtml\Label\Grid::getOptionArray23()
							]
						);

						
				$this->addColumn(
					'printed_at',
					[
						'header' => __('Printed at'),
						'index' => 'printed_at',
						'type'      => 'datetime',
					]
				);

					
				$this->addColumn(
					'created_at',
					[
						'header' => __('Created at'),
						'index' => 'created_at',
						'type'      => 'datetime',
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
                        //'field' => 'label_id'
                    //]
                //],
                //'filter' => false,
                //'sortable' => false,
                //'index' => 'stores',
                //'header_css_class' => 'col-action',
                //'column_css_class' => 'col-action'
            //]
        //);
		

		
		   $this->addExportType($this->getUrl('unifaun/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('unifaun/*/exportExcel', ['_current' => true]),__('Excel XML'));

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

        $this->setMassactionIdField('label_id');
        //$this->getMassactionBlock()->setTemplate('Pcxpress_Unifaun::label/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('label');

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
     * @param \Pcxpress\Unifaun\Model\label|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'unifaun/*/edit',
            ['label_id' => $row->getId()]
        );
		
    }

	
		static public function getOptionArray23()
		{
            $data_array=array(); 
			$data_array[0]='Yes';
            return($data_array);
		}
		static public function getValueArray23()
		{
            $data_array=array();
			foreach(\Pcxpress\Unifaun\Block\Adminhtml\Label\Grid::getOptionArray23() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);
			}
            return($data_array);

		}
		

}