<?php

namespace Pcxpress\Unifaun\Model\Carrier\CalculationMethod;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class Unit extends \Pcxpress\Unifaun\Model\Carrier\CalculationMethod\CalculationAbstract
{
    const ORDER = "title";
    const COUNTRY_SWEDEN = 'SE';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $shippingRateResultFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $catalogProductFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Shipping\Model\Rate\ResultFactory $shippingRateResultFactory,
        \Magento\Catalog\Model\ProductFactory $catalogProductFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->shippingRateResultFactory = $shippingRateResultFactory;
        $this->catalogProductFactory = $catalogProductFactory;
    }

    /**
     * Calculate the price based on unit
     *
     * @param RateRequest $request
     * @param Result $result
     *
     * @return \Magento\Shipping\Model\Rate\Result
     */
    public function getRateResult(
        RateRequest $request,
        Result $result
    )
    {

        $methods = $this->getShippingMethods();

        $methods->addFilter('active', 1);
        $methods->addOrder(self::ORDER, "asc");

        $storeId = $this->getRequest()->getStoreId();
        $shippingPriceMax = (int)$this->scopeConfig->getValue("carriers/unifaun/shipping_max_unit_price", \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);

//        $result = $this->shippingRateResultFactory->create();

        $destCountryId = $this->getRequest()->getDestCountryId();
        $destZipCode = intval($this->getRequest()->getDestPostcode());

        $request = $this->getRequest();
        $storeId = $request->getStoreId();

        // Check which attribute we should use, either default attribute or one of the zones
        $priceAttributeName = $this->scopeConfig->getValue("carriers/unifaun/shipping_unit_price", \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        if ($destCountryId === self::COUNTRY_SWEDEN && $destZipCode) {
            // Check if zone 1
            $swedenZipCodeZone1 = explode(',', $this->scopeConfig->getValue("carriers/unifaun/shipping_se_zone1_zip_codes", \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId));

            if (count($swedenZipCodeZone1) === 2) {
                foreach ($swedenZipCodeZone1 as $zipCodes) {
                    $zipCodes = trim($zipCodes);
                    $zipCodes = explode('-', $zipCodes);

                    if (count($zipCodes) == 1) {
                        $zipCodes[1] = $zipCodes[0];
                    } elseif (count($zipCodes) > 2) {
                        continue;
                    }

                    array_walk($zipCodes, 'intval');

                    if ($zipCodes[0] <= $destZipCode && $zipCodes[1] >= $destZipCode) {
                        $priceAttributeName = $this->scopeConfig->getValue("carriers/unifaun/shipping_se_zone1_unit_price", \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
                        break;
                    }
                }
            }
        }

        $items = $this->getRequest()->getAllItems();
        $products = array();


        foreach ($methods as $method) {
            if ($method->getVisibleFrontend() != 1) {
                // continue;
            }

            // Check if this shipping method is enabled for this store view
            $storeIds = is_array($method->getStoreIds()) ? $method->getStoreIds() : array();
            if (!(in_array(0, $storeIds) || in_array($storeId, $storeIds))) {
                continue;
            }

            $totalPrice = $this->getTotalPrice($items, $priceAttributeName);

            $resultMethod = $this->getResultMethod($method, $totalPrice);

            $result->append($resultMethod);
        }


        return $result;
    }

    public function getTotalPrice($items)
    {
        $totalPrice = 0;

        foreach ($items as $item) {
            if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                continue;
            }
            $product = $item->getProduct();

            $product = $this->catalogProductFactory->create()->load($product->getId());
            $shippingPrice = floatval($product->getData($priceAttributeName));

            if ($shippingPrice > $shippingPriceMax) {
                $totalPrice = $shippingPriceMax;
            } elseif ($shippingPrice > $totalPrice) {
                $totalPrice = $shippingPrice;
            }
        }
        return $totalPrice;
    }

    public function getResultMethod($method, $totalPrice)
    {
        $resultMethod = Mage::getModel('shipping/rate_result_resultMethod');
        $resultMethod->setCarrier($this->getCode());
        $resultMethod->setCarrierTitle($this->scopeConfig->getValue('carriers/' . $this->getCode(\Magento\Store\Model\ScopeInterface::SCOPE_STORE) . '/title'));

        $resultMethod->setMethod($method->getId());
        $resultMethod->setMethodTitle($method->getTitle());

        $resultMethod->setPrice($totalPrice);
        return $resultMethod;
    }

}