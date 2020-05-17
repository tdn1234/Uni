<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model;

class ShippingRate extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingRate\CollectionFactory
     */
    protected $unifaunMysql4ShippingRateCollectionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Pcxpress\Unifaun\Model\Mysql4\ShippingRate\CollectionFactory $unifaunMysql4ShippingRateCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->unifaunMysql4ShippingRateCollectionFactory = $unifaunMysql4ShippingRateCollectionFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function _construct()
    {
        $this->_init('Pcxpress\Unifaun\Model\Mysql4\ShippingRate');
    }
		
		public function getRateId($shippingMethodId)
		{
			//get first one
			$collection = $this->unifaunMysql4ShippingRateCollectionFactory->create()
			->addFieldToFilter('shippingmethod_id')
			->addOrder('shippingrate_id', 'ASC')
			->getSelect()->limit(1);
			$data = $collection->getData();
			return (count($data))? $data[0]['shippingrate_id'] : '';
		}
}