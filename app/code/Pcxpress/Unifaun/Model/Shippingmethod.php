<?php

namespace Pcxpress\Unifaun\Model;


use Pcxpress\Unifaun\Model\ShippingrateFactory;

class Shippingmethod extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var ShippingrateFactory $shippingrateFactory */
    protected $shippingrateFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        ShippingrateFactory $shippingrateFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->shippingrateFactory = $shippingrateFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Pcxpress\Unifaun\Model\ResourceModel\Shippingmethod');
    }

    /**
     * Get all prices for this collection
     * @return \Pcxpress\Unifaun\Model\Mysql4\ShippingRate\Collection
     */
    public function getRates()
    {
        $calculationAttribute = $this->getCalculationAttribute();

        $collection = $this->shippingrateFactory->create()->getCollection();
        $collection->addFieldToFilter("shippingmethod_id", $this->getId());
        $collection->addOrder('zipcode_range', 'ASC');
        $collection->addOrder($calculationAttribute, 'ASC');
        return $collection;
    }

    public function getCalculationAttribute()
    {
        $calculationAttribute = $this->scopeConfig->getValue('carriers/unifaun/sectionheading_admin/calculation_attribute', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return ($calculationAttribute) ? $calculationAttribute : 'max_weight';
    }
}