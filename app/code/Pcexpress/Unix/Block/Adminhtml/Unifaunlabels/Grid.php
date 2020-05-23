<?php
namespace Pcexpress\Unix\Block\Adminhtml\Unifaunlabels;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Pcexpress\Unix\Model\unifaunlabelsFactory
     */
    protected $_unifaunlabelsFactory;

    /**
     * @var \Pcexpress\Unix\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Pcexpress\Unix\Model\unifaunlabelsFactory $unifaunlabelsFactory
     * @param \Pcexpress\Unix\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Pcexpress\Unix\Model\UnifaunlabelsFactory $UnifaunlabelsFactory,
        \Pcexpress\Unix\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_unifaunlabelsFactory = $UnifaunlabelsFactory;
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
        $collection = $this->_unifaunlabelsFactory->create()->getCollection();
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
						'header' => __('shipment id'),
						'index' => 'shipment_id',
					]
				);
				

						$this->addColumn(
							'status',
							[
								'header' => __('status'),
								'index' => 'status',
								'type' => 'options',
								'options' => \Pcexpress\Unix\Block\Adminhtml\Unifaunlabels\Grid::getOptionArray2()
							]
						);

						
				$this->addColumn(
					'printed_at',
					[
						'header' => __('printed_at'),
						'index' => 'printed_at',
						'type'      => 'datetime',
					]
				);

					
				$this->addColumn(
					'created_at',
					[
						'header' => __('created_at'),
						'index' => 'created_at',
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
		

		
		   $this->addExportType($this->getUrl('unix/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('unix/*/exportExcel', ['_current' => true]),__('Excel XML'));

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
        //$this->getMassactionBlock()->setTemplate('Pcexpress_Unix::unifaunlabels/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('unifaunlabels');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('unix/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('unix/*/massStatus', ['_current' => true]),
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
        return $this->getUrl('unix/*/index', ['_current' => true]);
    }

    /**
     * @param \Pcexpress\Unix\Model\unifaunlabels|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'unix/*/edit',
            ['label_id' => $row->getId()]
        );
		
    }

	
		static public function getOptionArray2()
		{
            $data_array=array(); 
			$data_array[0]='Yes';
			$data_array[1]='No';
            return($data_array);
		}
		static public function getValueArray2()
		{
            $data_array=array();
			foreach(\Pcexpress\Unix\Block\Adminhtml\Unifaunlabels\Grid::getOptionArray2() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);
			}
            return($data_array);

		}
		

}