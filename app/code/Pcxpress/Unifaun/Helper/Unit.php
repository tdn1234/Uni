<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Helper;

class Unit extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct(
            $context
        );
    }

    protected function _getConfigValue($field) {
        return $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Model\Carrier\ShippingMethod::CARRIER_CODE . '/' . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function viewLength($length)
    {
        return $length;
    }

    public function viewWeight($weight)
    {
        return $weight;
    }

    public function convertLengthFromStoreToUnifaun($length)
    {
        $storeUnit = $this->getStoreLengthUnit();
        $unifaunUnit = $this->getUnifaunLengthUnit();

        if ($storeUnit == $unifaunUnit) {
            return $length;
        }

        if ($storeUnit == "cm" && $unifaunUnit == "m") {
            return floatval($length)/1000;
        } elseif ($storeUnit == "m" && $unifaunUnit == "cm") {
            return floatval($length)/1000;
        }

        throw new \Exception("Unable to convert selected units (from " . $storeUnit . " to " . $unifaunUnit . ")");
    }

    public function convertLengthFromUnifaunToStore($length)
    {
        return $length;
    }

    public function convertWeightFromStoreToUnifaun($weight)
    {
        $storeUnit = $this->getStoreWeightUnit();
        $unifaunUnit = $this->getUnifaunWeightUnit();

        if ($storeUnit == $unifaunUnit) {
            return $weight;
        }

        if ($storeUnit == "g" && $unifaunUnit == "kg") {
            return floatval($weight)/1000;
        }

        throw new \Exception("Unable to convert selected units (from " . $storeUnit . " to " . $unifaunUnit . ")");
    }

    public function convertWeightFromUnifaunToStore($weight)
    {
        return $weight;
    }

    public function getStoreWeightUnit()
    {
        return $this->_getConfigValue("unit_weight");
    }

    public function getStoreLengthUnit()
    {
        return $this->_getConfigValue("unit_length");
    }

    public function getUnifaunWeightUnit()
    {
        return "kg";
    }

    public function getUnifaunLengthUnit()
    {
        return "cm";
    }

}