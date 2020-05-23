<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\Pickuplocation;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Pcxpress\Unifaun\Model\pickuplocationFactory
     */
    protected $_pickuplocationFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Pcxpress\Unifaun\Model\pickuplocationFactory $pickuplocationFactory
     * @param \Pcxpress\Unifaun\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Pcxpress\Unifaun\Model\PickuplocationFactory $PickuplocationFactory,
        \Pcxpress\Unifaun\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_pickuplocationFactory = $PickuplocationFactory;
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
        $this->setDefaultSort('pickuplocation_id');
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
        $collection = $this->_pickuplocationFactory->create()->getCollection();
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
            'pickuplocation_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'pickuplocation_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );


		
				$this->addColumn(
					'name',
					[
						'header' => __('Name'),
						'index' => 'name',
					]
				);
				
				$this->addColumn(
					'address',
					[
						'header' => __('Address'),
						'index' => 'address',
					]
				);
				
				$this->addColumn(
					'postcode',
					[
						'header' => __('Postcode'),
						'index' => 'postcode',
					]
				);
				
				$this->addColumn(
					'city',
					[
						'header' => __('City'),
						'index' => 'city',
					]
				);
				
				$this->addColumn(
					'state',
					[
						'header' => __('State'),
						'index' => 'state',
					]
				);
				
				$this->addColumn(
					'countrycode',
					[
						'header' => __('Countrycode'),
						'index' => 'countrycode',
					]
				);
				
				$this->addColumn(
					'contact_person',
					[
						'header' => __('contact person'),
						'index' => 'contact_person',
					]
				);
				
				$this->addColumn(
					'phone',
					[
						'header' => __('phone'),
						'index' => 'phone',
					]
				);
				
				$this->addColumn(
					'mobile',
					[
						'header' => __('mobile'),
						'index' => 'mobile',
					]
				);
				
				$this->addColumn(
					'fax',
					[
						'header' => __('fax'),
						'index' => 'fax',
					]
				);
				
				$this->addColumn(
					'email',
					[
						'header' => __('email'),
						'index' => 'email',
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
                        //'field' => 'pickuplocation_id'
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

        $this->setMassactionIdField('pickuplocation_id');
        //$this->getMassactionBlock()->setTemplate('Pcxpress_Unifaun::pickuplocation/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('pickuplocation');

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
     * @param \Pcxpress\Unifaun\Model\pickuplocation|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'unifaun/*/edit',
            ['pickuplocation_id' => $row->getId()]
        );
		
    }

	

}