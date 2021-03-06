<?php

namespace Pcxpress\Unifaun\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class Tdncustom2 extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'tdncustom2';

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
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['tdncustom' => $this->getConfigData('name')];
    }

    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
//        return false;
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier('tdncustom2');
        $method->setCarrierTitle($this->getConfigData('title') . 'unifififi');

        $method->setMethod('unifaunnn1');
        $method->setMethodTitle($this->getConfigData('name') .  'unifaau');

        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        $amount = $this->getConfigData('price');
        $amount = 222;

        $method->setPrice($amount);
        $method->setCost($amount);



//        var_dump(get_class($result));
//        var_dump($method->getData());

        $result->append($method);



        $method = $this->_rateMethodFactory->create();

        $method->setCarrier('tdncustom2');
        $method->setCarrierTitle($this->getConfigData('title') . 'unifififi2222');

        $method->setMethod('unifaunnn2');
        $method->setMethodTitle($this->getConfigData('name') .  'unifaau22222');

        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        $amount = $this->getConfigData('price');
        $amount = 4444;

        $method->setPrice($amount);
        $method->setCost($amount);



//        var_dump(get_class($result));
//        var_dump($method->getData());

        $result->append($method);

//        $rates = $result->getAllRates();
//        foreach ($rates as $rate) {
//            var_dump($rate->getData());
//        }

        return $result;
    }
}