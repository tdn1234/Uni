<?php

namespace Pcxpress\Unifaun\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;
use Pcxpress\Unifaun\Helper\Shipping;
use Pcxpress\Unifaun\Model\Carrier\CalculationMethod\Weight;
use Pcxpress\Unifaun\Model\Carrier\CalculationMethod\Unit;

class Unifaun extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'unifaun';

    /** @var Pcxpress/Unifaun/Model/ShippingmethodFactory ShippingmethodFactory */
    protected $shippingmethodFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Helper\Shipping
     */
    protected $unifaunShippingHelper;

    /** @var Weight $weightCalculation */
    protected $weightCalculation;

    /** @var Unit $unitCalculation */
    protected $unitCalculation;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = [],
        ShippingmethodFactory $shippingmethodFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Pcxpress\Unifaun\Helper\Shipping $unifaunShippingHelper,
        Weight $weightCalculation,
        Unit $unitCalculation
    )
    {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->shippingmethodFactory = $shippingmethodFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->unifaunShippingHelper = $unifaunShippingHelper;
        $this->weightCalculation = $weightCalculation;
        $this->unitCalculation = $unitCalculation;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['unifaun' => $this->getConfigData('name')];
    }

    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
   {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $calculationMethod = $this->getCalculationModel($request);
        $result = $this->_rateResultFactory->create();

        return $calculationMethod->getRateResult($request, $result);
    }

    /**
     * @return Unit|Weight
     */
    protected function getCalculationModel()
    {
        $method = $this->scopeConfig->getValue('carriers/unifaun/sectionheading_admin/calculation_method', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        switch ($method) {
            case Shipping::WEIGHT_CALCULATION_METHOD:
                return $this->weightCalculation;
            case Shipping::UNIT_CALCULATION_METHOD:
                return $this->unitCalculation;
            default:
                return $this->weightCalculation;
        }
    }
}