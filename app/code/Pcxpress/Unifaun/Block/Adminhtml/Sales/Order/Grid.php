<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_UnifaunOrderGrid
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Block\Adminhtml\Sales\Order;

class Grid

    //extends IWD_OrderGrid_Block_Adminhtml_Sales_Order_Grid
{

    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\CollectionFactory
     */
    protected $directoryResourceModelCountryCollectionFactory;

    public function __construct(
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $directoryResourceModelCountryCollectionFactory
    )
    {
        $this->directoryResourceModelCountryCollectionFactory = $directoryResourceModelCountryCollectionFactory;
    }

    public function setCollection($collection)
    {
        $collection->getSelect()->join('sales_flat_order_address', 'main_table.entity_id = sales_flat_order_address.parent_id AND sales_flat_order_address.address_type = "shipping"',
            array('country_id')
        );
        return parent::setCollection($collection);

    }

    protected function _prepareColumns()
    {
        $this->addColumnAfter('country_id', array(
            'header' => __('Ship to Country'),
            'index' => 'country_id',
            'type' => 'options',
            'options' => $this->getAllCountry(),
            'filter_index' => 'sales_flat_order_address.country_id',
            'width' => '130px',
        ), 'shipping_name');

        return parent::_prepareColumns();;
    }

    public function getAllCountry()
    {
        $options = $this->directoryResourceModelCountryCollectionFactory->create()->load()->toOptionArray();
        $countries = array();
        foreach ($options as $option) {
            if ($option['value']) {
                $countries[$option['value']] = $option['label'];
            }
        }
        return $countries;
    }


}