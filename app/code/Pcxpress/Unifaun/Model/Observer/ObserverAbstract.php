<?php
namespace Pcxpress\Unifaun\Model\Observer;


class ObserverAbstract
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }
    /**
     * Get value from configuration
     *
     * @param $field
     * @return mixed
     */
    protected function _getConfigValue($field)
    {
        $value = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Model\Carrier\ShippingMethod::CARRIER_CODE . '/' . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->_scopeId);
        return $value;
    }

    /**
     * @param $key
     * @param array $array
     * @return null
     */
    protected function _getValue($key, array $array)
    {
        if (array_key_exists($key, $array)) {
            if ($array[$key]) {
                return $array[$key];
            }
        }
        return null;
    }
}