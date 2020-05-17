<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingRate;

class Grid extends \Magento\Backend\Block\Widget\Grid
{

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingRate\CollectionFactory
     */
    protected $unifaunMysql4ShippingRateCollectionFactory;

    /**
     * @var \Magento\Directory\Model\Config\Source\CountryFactory
     */
    protected $directoryConfigSourceCountryFactory;

    /**
     * @var \Magento\Config\Model\Config\Source\WebsiteFactory
     */
    protected $configConfigSourceWebsiteFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\Mysql4\ShippingRate\CollectionFactory $unifaunMysql4ShippingRateCollectionFactory,
        \Magento\Directory\Model\Config\Source\CountryFactory $directoryConfigSourceCountryFactory,
        \Magento\Config\Model\Config\Source\WebsiteFactory $configConfigSourceWebsiteFactory
    )
	{
        $this->unifaunMysql4ShippingRateCollectionFactory = $unifaunMysql4ShippingRateCollectionFactory;
        $this->directoryConfigSourceCountryFactory = $directoryConfigSourceCountryFactory;
        $this->configConfigSourceWebsiteFactory = $configConfigSourceWebsiteFactory;
		parent::__construct();

		$this->setId('unifaunGrid');
		$this->setDefaultSort('max_weight');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$request = $this->getRequest();
		$id = $request->getParam("method");

		$collection = $this->unifaunMysql4ShippingRateCollectionFactory->create()->addFieldToFilter(\Pcxpress\Unifaun\Helper\Data::SHIPPINGMETHOD_ID, $id);

		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	public function getUrl($route = '', $params = array())
	{
		$request = $this->getRequest();
		$params['method'] = $request->getParam("method");
		return parent::getUrl($route, $params);
	}

	protected function _prepareColumns()
	{
		$this->addColumn('shippingrate_id', array(
			'header' => Mage::helper('core')->__('ID'),
			'align' => 'right',
			'width' => '50px',
			'index' => 'shippingrate_id',
		));

		$this->addColumn('max_weight', array(
			'header' => Mage::helper('core')->__('Max Weight'),
			'align' => 'left',
			'index' => 'max_weight',
		));

		$this->addColumn('max_width', array(
			'header' => Mage::helper('core')->__('Max Width'),
			'align' => 'left',
			'index' => 'max_width',
		));

		$this->addColumn('max_height', array(
			'header' => Mage::helper('core')->__('Max Height'),
			'align' => 'left',
			'index' => 'max_height',
		));

		$this->addColumn('max_depth', array(
			'header' => Mage::helper('core')->__('Max Depth'),
			'align' => 'left',
			'index' => 'max_depth',
		));

		$options = array();
		foreach ($this->directoryConfigSourceCountryFactory->create()->toOptionArray(true) as $option) {
				$options[$option['value']] = $option['label'];
		}

		$this->addColumn('countries', array(
			'header' => Mage::helper('core')->__('Countries'),
			'align' => 'left',
			'index' => 'countries',
			'type'      => 'options',
			'options' => $options,
			'renderer' => 'unifaun/adminhtml_shippingRate_Renderer_countries',
			'filter_condition_callback' => array($this, '_countryFilter'),
		));

		if (!Mage::app()->isSingleStoreMode()) {
			$options = array('0' => Mage::helper('core')->__("(all)"));
			foreach ($this->configConfigSourceWebsiteFactory->create()->toOptionArray(true) as $option) {
					$options[$option['value']] = $option['label'];
			}
			$this->addColumn('website_id', array(
				'header' => Mage::helper('core')->__('Websites'),
				'align' => 'left',
				'index' => 'website_id',
				'type' => 'options',
				'options' => $options,
			));
		}
		
		$this->addColumn('zipcode_range', array(
			'header' => Mage::helper('core')->__('Zip Codes'),
			'align' => 'left',
			'index' => 'zipcode_range',
		));

		$this->addColumn('shipping_fee', array(
			'header' => Mage::helper('core')->__('Shipping Fee'),
			'align' => 'left',
			'index' => 'shipping_fee',
		));

		return parent::_prepareColumns();
	}

	protected function _countryFilter($collection, $column)
	{
			if (!$value = $column->getFilter()->getValue()) {
					return $this;
			}

			$collection->getSelect()->where(
					"countries like ?", "%$value%");

			return $this;
	}

	protected function _prepareMassaction()
	{

			return $this;
	}

	public function getRowUrl($row)
	{
			return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

}