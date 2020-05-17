<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\PickupLocation;

class Grid extends \Magento\Backend\Block\Widget\Grid {

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\PickupLocation\CollectionFactory
     */
    protected $unifaunMysql4PickupLocationCollectionFactory;

    /**
	 * Constructor
	 * @param null	
	 * @return null
	 */	
	public function __construct(
        \Pcxpress\Unifaun\Model\Mysql4\PickupLocation\CollectionFactory $unifaunMysql4PickupLocationCollectionFactory
    )
	{
        $this->unifaunMysql4PickupLocationCollectionFactory = $unifaunMysql4PickupLocationCollectionFactory;
		parent::__construct();
		$this->setId('unifaunGrid');
		$this->setDefaultSort('city');
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
		$collection = $this->unifaunMysql4PickupLocationCollectionFactory->create();
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
		$this->addColumn('pickuplocation_id', array(
				'header' => __('ID'),
				'align' => 'right',
				'width' => '50px',
				'index' => 'pickuplocation_id',
		));

		$this->addColumn('name', array(
				'header' => __('Name'),
				'align' => 'left',
				'type' => 'text',
				'index' => 'name',
		));

		$this->addColumn('address', array(
				'header' => __('Address'),
				'align' => 'left',
				'type' => 'text',
				'index' => 'address',
		));

		$this->addColumn('city', array(
				'header' => __('City'),
				'align' => 'left',
				'type' => 'text',
				'index' => 'city',
		));

		$this->addColumn('postcode', array(
				'header' => __('Zip Code'),
				'align' => 'left',
				'type' => 'text',
				'width' => '50px',
				'index' => 'postcode',
		));
		$this->addColumn('State', array(
				'header' => __('State/Region'),
				'align' => 'left',
				'type' => 'text',
				'index' => 'state',
		));
		$this->addColumn('countrycode', array(
				'header' => __('Country code'),
				'align' => 'left',
				'type' => 'text',
				'width' => '50px',
				'index' => 'countrycode',
		));
		return parent::_prepareColumns();
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