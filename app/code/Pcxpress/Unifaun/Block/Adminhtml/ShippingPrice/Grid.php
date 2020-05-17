<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingPrice;

class Grid extends \Magento\Backend\Block\Widget\Grid
{

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingPrice\CollectionFactory
     */
    protected $unifaunMysql4ShippingPriceCollectionFactory;

    /**
     * @var \Magento\Directory\Model\Config\Source\CountryFactory
     */
    protected $directoryConfigSourceCountryFactory;

    /**
     * @var \Magento\Config\Model\Config\Source\WebsiteFactory
     */
    protected $configConfigSourceWebsiteFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\Mysql4\ShippingPrice\CollectionFactory $unifaunMysql4ShippingPriceCollectionFactory,
        \Magento\Directory\Model\Config\Source\CountryFactory $directoryConfigSourceCountryFactory,
        \Magento\Config\Model\Config\Source\WebsiteFactory $configConfigSourceWebsiteFactory
    )
    {
        $this->unifaunMysql4ShippingPriceCollectionFactory = $unifaunMysql4ShippingPriceCollectionFactory;
        $this->directoryConfigSourceCountryFactory = $directoryConfigSourceCountryFactory;
        $this->configConfigSourceWebsiteFactory = $configConfigSourceWebsiteFactory;
        parent::__construct();

        $this->setId('unifaunGrid');
        $this->setDefaultSort('weight_max');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $request = $this->getRequest();
        $id = $request->getParam("method");

        $collection = $this->unifaunMysql4ShippingPriceCollectionFactory->create();
        $collection->addFieldToFilter("shippingmethod_id", $id);

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
        $this->addColumn('shippingprice_id', array(
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'shippingprice_id',
        ));

        $this->addColumn('weight_max', array(
            'header' => __('Max Weight'),
            'align' => 'left',
            'index' => 'weight_max',
        ));

        $this->addColumn('width_max', array(
            'header' => __('Max Width'),
            'align' => 'left',
            'index' => 'width_max',
        ));

        $this->addColumn('height_max', array(
            'header' => __('Max Height'),
            'align' => 'left',
            'index' => 'height_max',
        ));

        $this->addColumn('depth_max', array(
            'header' => __('Max Depth'),
            'align' => 'left',
            'index' => 'depth_max',
        ));

        $options = array();
        foreach ($this->directoryConfigSourceCountryFactory->create()->toOptionArray(true) as $option) {
            $options[$option['value']] = $option['label'];
        }

        $this->addColumn('countries', array(
            'header' => __('Countries'),
            'align' => 'left',
            'index' => 'countries',
            'type'      => 'options',
            'options' => $options,
            'renderer' => 'unifaun/adminhtml_shippingPrice_countriesRenderer',
            'filter_condition_callback' => array($this, '_countryFilter'),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $options = array('0' => __("(all)"));
            foreach ($this->configConfigSourceWebsiteFactory->create()->toOptionArray(true) as $option) {
                $options[$option['value']] = $option['label'];
            }
            $this->addColumn('website_id', array(
                'header' => __('Websites'),
                'align' => 'left',
                'index' => 'website_id',
                'type' => 'options',
                'options' => $options,
            ));
        }
        
        $this->addColumn('zipcode_ranges', array(
            'header' => __('Zip Codes'),
            'align' => 'left',
            'index' => 'zipcode_ranges',
        ));

        $this->addColumn('shipping_fee', array(
            'header' => __('Shipping Fee'),
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