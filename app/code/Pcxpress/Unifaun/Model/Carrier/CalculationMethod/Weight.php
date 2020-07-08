<?php

namespace Pcxpress\Unifaun\Model\Carrier\CalculationMethod;


use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
use Pcxpress\Unifaun\Model\Shippingmethod;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;
use Pcxpress\Unifaun\Model\Carrier\CalculationMethod\CalculationAbstract;

class Weight extends CalculationAbstract
{
    const FILTER = "active";

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $shippingRateResultFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $quoteQuoteAddressRateResultMethodFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $shippingmethodFactory;

    /** @var MethodFactory $rateMethodFactory */
    protected $rateMethodFactory;

    public function __construct(
        \Magento\Shipping\Model\Rate\ResultFactory $shippingRateResultFactory,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $quoteQuoteAddressRateResultMethodFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        ShippingmethodFactory $shippingmethodFactory,
        MethodFactory $rateMethodFactory
    )
    {
        $this->shippingRateResultFactory = $shippingRateResultFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->storeManager = $storeManager;
        $this->quoteQuoteAddressRateResultMethodFactory = $quoteQuoteAddressRateResultMethodFactory;
        $this->scopeConfig = $scopeConfig;
        $this->shippingmethodFactory = $shippingmethodFactory;
        $this->rateMethodFactory = $rateMethodFactory;
    }

    /**
     * Calculate the price based on weight
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
        $methods = $this->shippingmethodFactory->create()->getCollection();


        $destinationCountryId = $request->getDestCountryId();
        $destinationZipCode = $request->getDestPostcode();
        $weight = (float)$request->getFreeMethodWeight();

        if (!$weight && $this->unifaunHelper->getCalculateZeroWeight()) {
            $weight = 1;
        }

        $current_store_id = $this->storeManager->getStore()->getStoreId();


        $websiteId = $request->getWebsiteId();
        $storeId = $request->getStoreId();

        $allItems = $request->getAllItems();

        $shippingGroup = $this->getShippingGroup($allItems);


        foreach ($methods as $shippingMethod) {


            $shippingMethodGroup = $shippingMethod->getShippingGroup();
            $shippingMethodGroupArray = [];
            if ($shippingMethodGroup) {
                $shippingMethodGroupArray = explode(',', $shippingMethodGroup);
            }


            // create new instance of method rate
            $method = $this->rateMethodFactory->create();
            // var_dump($current_store_id);die;

            //0 is admin store
            if ($shippingMethod->getFrontendVisibility() != 1 && $current_store_id != 0) {

                continue;
            }

            // Check if the consignment is within the set range.
            if ($shippingMethod->getMaxConsignmentWeight() != 0 && ($weight < $shippingMethod->getMinWeight() || $weight > $shippingMethod->getMaxConsignmentWeight())) {

                continue;
            }

// var_dump($shippingGroup);
// var_dump($shippingMethodGroupArray);
            if (!$this->isShippingMethodMatched($shippingGroup, $shippingMethodGroupArray)) {

                // continue;
            }

            // Check if this shipping method is enabled for this store view
            $shippingStoreIds = $shippingMethod->getStoreIds();

            if ( !is_array($shippingStoreIds)) {
                $shippingStoreIds = explode(',', $shippingStoreIds);
            }

            if (!$this->shippingMethodEnabledForStore($shippingStoreIds, $storeId)) {

               continue;
            }

            $totalPrice = 0;

            $rates = $this->getShippingRates($shippingMethod, $destinationCountryId, $websiteId);
//            var_dump($rates);die;

            if (!count($rates)) {
                continue;
            }


            $weightLeft = $weight;
            $packages = array();

            while ($weightLeft > 0) {
                $foundPrice = false;

                foreach ($rates as $rate) {

                    if ($weightLeft > $rate->getMaxWeight()) {
                        // The weight left is bigger than the maximum allowed weight.
                        // If this is the last package size, we need to split into several packages.
                        continue;
                    }

                    $weightLeft = 0;
                    $packages[] = $rate;

                    $foundPrice = true;
                    break;
                }

                if ($foundPrice === false) {
                    // If we didn't find a rate that could fit the remaining weight,
                    // we need to take the last rate.
                    $lastPrice = reset($rates);
                    $weightLeft -= $lastPrice->getMaxWeight();
                    $packages[] = $lastPrice;
                }
            }

            // We have all packages that we need

            foreach ($packages as $package) {
                $totalPrice += $package->getShippingFee();
            }

            // Set that we are using Pcxpress Unifaun
            $method->setCarrier($this->_code);
            //$method->setCarrierTitle($this->scopeConfig->getValue('carriers/' . $this->getCode(\Magento\Store\Model\ScopeInterface::SCOPE_STORE) . '/title'));
            $method->setCarrierTitle($shippingMethod->getTitle());

            // Record information about this method so we can track it
            $method->setMethod($shippingMethod->getId());
            $method->setMethodTitle($shippingMethod->getTitle());
            $totalPrice = $totalPrice + $shippingMethod->getHandlingFee();

            if ($shippingMethod->getFreeShippingEnable()) {
                // Get the total order amount
                $freeShipping = floatval($shippingMethod->getFreeShippingSubtotal());
                $totalValueWithDiscount = $request->getPackageValueWithDiscount();
                if ($totalValueWithDiscount >= $freeShipping) {
                    $totalPrice = 0;
                }
            }

            $method->setPrice($totalPrice);
            $result->append($method);
        }


        return $result;
    }

    /**
     * Calculate the price based on weight
     * @return \Magento\Shipping\Model\Rate\Result
     */
    public function getRateResult1(\Magento\Shipping\Model\Rate\Result $result)
    {

        $request = $this->getRequest();

        $methods = $this->shippingmethodFactory->create()->getCollection();


        $destinationCountryId = $request->getDestCountryId();
        $destinationZipCode = $request->getDestPostcode();
        $weight = (float)$request->getFreeMethodWeight();

        if (!$weight && $this->unifaunHelper->getCalculateZeroWeight()) {
            $weight = 1;
        }

        $current_store_id = $this->storeManager->getStore()->getStoreId();


        $websiteId = $request->getWebsiteId();
        $storeId = $request->getStoreId();

        $allItems = $request->getAllItems();

        $shippingGroup = $this->getShippingGroup($allItems);


        foreach ($methods as $shippingMethod) {

            $shippingMethodGroup = $shippingMethod->getShippingGroup();
            $shippingMethodGroupArray = [];
            if ($shippingMethodGroup) {
                $shippingMethodGroupArray = explode(',', $shippingMethodGroup);
            }

            // create new instance of method rate
            $method = $this->quoteQuoteAddressRateResultMethodFactory->create();
            // var_dump($current_store_id);die;

            //0 is admin store
            if ($shippingMethod->getFrontendVisibility() != 1 && $current_store_id != 0) {

                continue;
            }

            // Check if the consignment is within the set range.
            if ($shippingMethod->getMaxConsignmentWeight() != 0 && ($weight < $shippingMethod->getMinWeight() || $weight > $shippingMethod->getMaxConsignmentWeight())) {

                continue;
            }

            if (!$this->isShippingMethodMatched($shippingGroup, $shippingMethodGroupArray)) {

               continue;
            }

            // Check if this shipping method is enabled for this store view
            $shippingStoreIds = is_array($shippingMethod->getStoreIds()) ? $shippingMethod->getStoreIds() : array();

            if (!$this->shippingMethodEnabledForStore($shippingStoreIds, $storeId)) {

                //continue;
            }

            $rates = $this->getShippingRates($shippingMethod, $destinationCountryId, $websiteId);


            if (!count($rates)) {
                continue;
            }


            $weightLeft = $weight;
            $packages = array();

            while ($weightLeft > 0) {
                $foundPrice = false;

                foreach ($rates as $rate) {

                    if ($weightLeft > $rate->getMaxWeight()) {
                        // The weight left is bigger than the maximum allowed weight.
                        // If this is the last package size, we need to split into several packages.
                        continue;
                    }

                    $weightLeft = 0;
                    $packages[] = $rate;

                    $foundPrice = true;
                    break;
                }

                if ($foundPrice === false) {
                    // If we didn't find a rate that could fit the remaining weight,
                    // we need to take the last rate.
                    $lastPrice = reset($rates);
                    $weightLeft -= $lastPrice->getMaxWeight();
                    $packages[] = $lastPrice;
                }
            }

            // We have all packages that we need
            $totalPrice = 0;
            foreach ($packages as $package) {
                $totalPrice += $package->getShippingFee();
            }

            // Set that we are using Pcxpress Unifaun
            $method->setCarrier('unifaun');
            $method->setCarrierTitle($this->scopeConfig->getValue('carriers/' . $this->getCode(\Magento\Store\Model\ScopeInterface::SCOPE_STORE) . '/title'));

            // Record information about this method so we can track it
            $method->setMethod($shippingMethod->getId());
            $method->setMethodTitle($shippingMethod->getTitle());
            $totalPrice = $totalPrice + $shippingMethod->getHandlingFee();

            if ($shippingMethod->getFreeShippingEnable()) {
                // Get the total order amount
                $freeShipping = floatval($shippingMethod->getFreeShippingSubtotal());
                $totalValueWithDiscount = $request->getPackageValueWithDiscount();
                if ($totalValueWithDiscount >= $freeShipping) {
                    $totalPrice = 0;
                }
            }

            $method->setPrice($totalPrice);

            $result->append($method);
        }


        return $result;
    }


    public function getShippingRates($shippingMethod, $destinationCountryId, $websiteId)
    {

        $priceListDefault = array();
        $priceListZipCodes = array();

        $rates = $shippingMethod->getRates();


        foreach ($rates as $priceConfiguration) {
            $countryCodes = $priceConfiguration->getCountries();
            if ($countryCodes &&  count($countryCodes) > 0 && !in_array($destinationCountryId, $countryCodes)) {

                continue;
            }

            if (!($priceConfiguration->getWebsiteId() == 0 || $priceConfiguration->getWebsiteId() == $websiteId)) {

                continue;
            }

            if (!$priceConfiguration->getMaxWeight() > 0) {

                continue;
            }

            // Check zip code ranges
            $zipCodeRangeString = $priceConfiguration->getZipcodeRanges();
            if ($zipCodeRangeString) {
                $zipCodeRanges = explode(',', $zipCodeRangeString);
                array_walk($zipCodeRanges, 'trim');

                foreach ($zipCodeRanges as $zipCodeRange) {
                    $range = explode('-', $zipCodeRange);
                    array_walk($range, 'trim');
                    array_walk($range, 'intval');
                    if (count($range) == 1) {
                        // If we don't have a range but a specific value we expand it to a range
                        $range[1] = $range[0];
                    } elseif (count($range) != 2) {
                        // Invalid range definition
                        continue;
                    }

                    // Make sure the user entered the zip codes in the right order, 10000-20000 instead of 20000-10000.
                    sort($range, SORT_NUMERIC);

                    // Check if we are within the range
                    if ($destinationZipCode >= $range[0] && $destinationZipCode <= $range[1]) {
                        $priceListZipCodes[] = $priceConfiguration;
                        break;
                    }
                }
            } else {
                // No zip code range given,
                $priceListDefault[] = $priceConfiguration;
            }
        }

        $rates = $priceListZipCodes;
        if (count($priceListZipCodes) === 0) {
            $rates = $priceListDefault;
        }

        return $rates;
    }

    protected function shippingMethodEnabledForStore(array $shippingStoreIds, $storeId)
    {
        
        if ((in_array(0, $shippingStoreIds) || in_array($storeId, $shippingStoreIds))) {
            return true;
        }
        return false;
    }

    protected function isShippingMethodMatched($shippingGroup, $shippingMethodGroupArray)
    {
        if (!in_array($shippingGroup, $shippingMethodGroupArray)) {
            return false;
        }
        return true;
    }

    /**
     * Get the shipping group of the order from a collection of items.
     *
     * @param array $items
     * @return mixed
     */
    protected function getShippingGroup(array $items)
    {
        $shippingGroups = array();
        foreach ($items as $item) {
            /* @var $item Mage_Sales_Model_Quote_Item */
            $shippingGroups[] = $this->getShippingGroupForProduct($item->getProduct());
        }

        $shippingGroupSorting = $this->getShippingGroupSorting();

        if ($shippingGroupSorting == 'desc') {
            $shippingGroup = max($shippingGroups);
        } else {
            $shippingGroup = min($shippingGroups);
        }

        return $shippingGroup;
    }

}