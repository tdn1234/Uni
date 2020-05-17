<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\PickupAddress;

class Grid extends \Magento\Backend\Block\Widget\Grid {

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\PickupAddress\CollectionFactory
     */
    protected $unifaunMysql4PickupAddressCollectionFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\Mysql4\PickupAddress\CollectionFactory $unifaunMysql4PickupAddressCollectionFactory
    )
    {
        $this->unifaunMysql4PickupAddressCollectionFactory = $unifaunMysql4PickupAddressCollectionFactory;
        parent::__construct();
        $this->setId('unifaunGrid');
        $this->setDefaultSort('address_city');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->unifaunMysql4PickupAddressCollectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('pickupaddress_id', array(
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'pickupaddress_id',
        ));

        $this->addColumn('address_name', array(
            'header' => __('Name'),
            'align' => 'left',
            'type' => 'text',
            'index' => 'address_name',
        ));

        $this->addColumn('address_address1', array(
            'header' => __('Address'),
            'align' => 'left',
            'type' => 'text',
            'index' => 'address_address1',
        ));

        $this->addColumn('address_city', array(
            'header' => __('City'),
            'align' => 'left',
            'type' => 'text',
            'index' => 'address_city',
        ));

        $this->addColumn('address_postcode', array(
            'header' => __('Zip Code'),
            'align' => 'left',
            'type' => 'text',
            'index' => 'address_postcode',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}