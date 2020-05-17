<?php
namespace Pcxpress\Unifaun\Model\Carrier\CalculationMethod;


abstract class CalculationAbstract
{

    protected $_request;
    protected $_shippingMethods;
    protected $_code;

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory
     */
    protected $unifaunMysql4ShippingMethodCollectionFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
        $this->scopeConfig = $scopeConfig;
    }
    /**
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $request
     * @return \Pcxpress\Unifaun\Model\Carrier\CalculationMethod\Weight
     */
    public function setRequest(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * @return \Magento\Quote\Model\Quote\Address\RateRequest
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @param string $code
     * @return \Pcxpress\Unifaun\Model\Carrier\CalculationMethod\Abstract
     */
    public function setCode($code)
    {
        $this->_code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @return \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\Collection
     */
    public function getShippingMethods()
    {
        return $this->unifaunMysql4ShippingMethodCollectionFactory->create();;
    }

    /**
     * @return string
     */
    public function getWidthAttribute()
    {
        return $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/width', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getHeightAttribute()
    {
        return $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getDepthAttribute()
    {
        return $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/depth', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getShippingGroupAttribute()
    {
        return $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/shipping_group', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getDefaultShippingGroupValue()
    {
        return $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/shipping_group_value_default', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getShippingGroupSorting()
    {
        return $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/shipping_group_sorting', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param array $extraOptions
     * @return string
     */
    public function getShippingGroupForProduct(\Magento\Catalog\Model\Product $product, $extraOptions = array())
    {
        if (!$this->getShippingGroupAttribute()) {
            return $this->getDefaultShippingGroupValue();
        }

        $product->load($product->getId(), array($this->getShippingGroupAttribute()));
        $value = $product->getData($this->getShippingGroupAttribute());

        if (!$value) {
            return $this->getDefaultShippingGroupValue();
        }

        return $value;
    }

    /**
     * Get the different rate results
     * @abstract
     * @return \Magento\Shipping\Model\Tracking\Result
     */
    abstract function getRateResult();

}