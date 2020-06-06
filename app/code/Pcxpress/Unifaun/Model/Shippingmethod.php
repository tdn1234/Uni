<?php
namespace Pcxpress\Unifaun\Model;

class Shippingmethod extends \Magento\Framework\Model\AbstractModel
{
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