<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 *
 */
namespace Pcxpress\Unifaun\Model;

class ShippingMethodbk extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingRate\CollectionFactory
     */
    protected $unifaunMysql4ShippingRateCollectionFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Pcxpress\Unifaun\Model\Mysql4\ShippingRate\CollectionFactory $unifaunMysql4ShippingRateCollectionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->unifaunMysql4ShippingRateCollectionFactory = $unifaunMysql4ShippingRateCollectionFactory;
        $this->scopeConfig = $scopeConfig;
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
		$this->_init('Pcxpress\Unifaun\Model\Mysql4\ShippingMethod');
	}

	/**
	 * Get all prices for this collection
	 * @return \Pcxpress\Unifaun\Model\Mysql4\ShippingRate\Collection
	 */
	public function getRates()
	{
		$calculationAttribute = $this->getCalculationAttribute();

		$collection = $this->unifaunMysql4ShippingRateCollectionFactory->create();
		$collection->addFieldToFilter("shippingmethod_id", $this->getId());
		$collection->addOrder('zipcode_range', 'ASC');
		$collection->addOrder($calculationAttribute, 'ASC');
		return $collection;
	}

	public function getCalculationAttribute(){
		$calculationAttribute = $this->scopeConfig->getValue($this->confPath . 'calculation_attribute', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		return ($calculationAttribute)? $calculationAttribute : 'max_weight';
	}
}