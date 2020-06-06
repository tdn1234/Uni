<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Carrier;

use Magento\Shipping\Model\Carrier\CarrierInterface;
use Pcxpress\Unifaun\Model\Carrier\CalculationMethod\CalculationAbstract;
use Pcxpress\Unifaun\Model\Carrier\CalculationMethod\Weight;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;
use Temando\Shipping\Model\Shipping\Carrier;

class ShippingMethod extends \Magento\Shipping\Model\Carrier\AbstractCarrier
      implements CarrierInterface
    //Mage_Shipping_Model_Carrier_Interface
{

    /**
     * @var string
     */
    protected $_code = 'unifaun';

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Helper\Shipping
     */
    protected $unifaunShippingHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Shipping\Model\Tracking\ResultFactory
     */
    protected $shippingTrackingResultFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\Result\StatusFactory
     */
    protected $shippingTrackingResultStatusFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory
     */
    protected $unifaunMysql4ShippingMethodCollectionFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory
     */
    protected $unifaunPcxpressUnifaunFactory;

    /**
     * @var Weight
     */
    protected $calculationWeight;

    protected $shippingmethodFactory;


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

    public function __construct1(
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Helper\Shipping $unifaunShippingHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Shipping\Model\Tracking\ResultFactory $shippingTrackingResultFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $shippingTrackingResultStatusFactory,
        \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory $unifaunPcxpressUnifaunFactory,
        Weight $calculationWeight,
        ShippingmethodFactory $shippingmethodFactory
    ) {
        $this->unifaunPcxpressUnifaunFactory = $unifaunPcxpressUnifaunFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunShippingHelper = $unifaunShippingHelper;
        $this->scopeConfig = $scopeConfig;
        $this->shippingTrackingResultFactory = $shippingTrackingResultFactory;
        $this->shippingTrackingResultStatusFactory = $shippingTrackingResultStatusFactory;
        $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
        $this->calculationWeight = $calculationWeight;
        $this->shippingmethodFactory = $shippingmethodFactory;
//        $shippingMethods = $shippingmethodFactory->create()->getCollection();
//        foreach ($shippingMethods as $shippingMethod) {
//            var_dump($shippingMethod->getData());die;
//        }
//        var_dump(get_class($shippingMethods));
//        var_dump(get_class_methods($shippingmethodFactory));
//        var_dump(get_class($shippingmethodFactory));die;
//        var_dump(get_class($calculationWeight));die;
    }
    public function isTrackingAvailable()
	{
			return true;
	}
	/**
	 * Collect rates for this shipping method based on information in $request
	 *
	 * @param \Magento\Quote\Model\Quote\Address\RateRequest $data
	 * @return \Magento\Shipping\Model\Rate\Result
	 */
	public function collectRates(\Magento\Quote\Model\Quote\Address\RateRequest $request)
	{

	    return $this->setFakeShippingMethod();
//	    var_dump($this->unifaunHelper->isMethodEnabled());
//		if (!$this->unifaunHelper->isMethodEnabled()) {
//				return false;
//		}

//		var_dump(get_class($request));die;
        $this->calculationWeight->setRequest($request);

        $result = $this->calculationWeight->getRateResult();
//        var_dump(get_class($result));
//        var_dump(get_class_methods($result));
//        $rates = $result->getAllRates();
//        foreach ($rates as $rate) {
//            var_dump($rate->getData());
//        }
        return $result;

		$calculationModel = $this->getCalculationModel();




		$calculationModel->setRequest($request);
		$calculationModel->setCode(\Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE);

		$result = $calculationModel->getRateResult();

		return $result;
	}

    protected function setFakeShippingMethod()
    {
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrierCode('unifaun');
        $method->setCarrierTitle('fffffff');

        $method->setMethod('tdncustomsss');
        $method->setMethodTitle('ssssssssssssssfasdf');

        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        $amount = 4007;

        $method->setPrice($amount);
        $method->setCost($amount);

//        var_dump($method->getData());

        $result->append($method);

        return $result;
    }

	public function getCalculationModel(){
		$calculationMethod = $this->unifaunShippingHelper->getCalculationMethod($this->scopeConfig->getValue('carriers/unifaun/calculation_method', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
		return Mage::getModel($calculationMethod);
	}

	/**
	 * Get tracking info for consignment
	 * @param int $trackingNo
	 * @return \Magento\Shipping\Model\Tracking\Result|boolean
	 */
	public function getTrackingInfo($trackingNo)
	{

		$result = $this->shippingTrackingResultFactory->create();

		$resultsArray = [];
		$progress = [];

		$unifaun = $this->unifaunPcxpressUnifaunFactory->create();

		$trackingData = $unifaun->trackConsignment($trackingNo);

				$trackingModel = $this->shippingTrackingResultStatusFactory->create();


		if (!$trackingData instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\StatusResult || ($trackingData->getErrors() != null) || $trackingData instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Error) {
			$resultsArray['status'] = "Kunde inte hämta informationen om försändelsen. Det kan bero på att fraktbolaget inte har registrerat försändelsen än. Kontakta kundtjänst för mer information.";
			$resultsArray['delivery_location'] = "";

		} else {

			$trackingStatus = $trackingData->getStatus();

			if (!is_array($trackingStatus)) {
					$trackingStatus = array($trackingStatus);
			}

			foreach ($trackingStatus as $status) {
				if (!$status instanceof \stdClass) {
						continue;
				}
				$progress = $this->getProgress($status);
			}

			$lastStatus = end($trackingStatus);
			if ($lastStatus instanceof \stdClass) {
				$time = strtotime($lastStatus->time);

				$resultsArray['status'] = $lastStatus->name;
				$resultsArray['delivery_date'] = date("Y-m-d", $time);
				$resultsArray['delivery_time'] = date("H:i", $time);
				$resultsArray['delivery_location'] = $lastStatus->location;
			}
		}

		$result = $this->getResult($result, $resultsArray, $trackingModel, $trackingNo, $progress);

		if($result instanceof \Magento\Shipping\Model\Tracking\Result){
			if ($trackings = $result->getAllTrackings()) {
					return $trackings[0];
			}
		}
		elseif (is_string($result) && !empty($result)) {
				return $result;
		}


		return false;
	}



	private function getResult($result, $resultsArray, $trackingModel, $trackingNo, $progress){

		if (count($resultsArray) > 0) {
			$trackingModel = $this->getTracking($trackingNo);
			$trackingModel->setProgressdetail(array($progress));
			$trackingModel->addData($resultsArray);
			$result->append($trackingModel);
		}
		return $result;
	}


	private function getTracking($trackingNo){
		$trackingModel = $this->shippingTrackingResultStatusFactory->create();
		$trackingModel->setCarrier(\Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE);
		$trackingModel->setCarrierTitle($this->getConfigData('title'));
		$trackingModel->setTracking($trackingNo);
		return $trackingModel;
	}

	private function getProgress($status){
		$progress = array();
		$time = strtotime($status->time);
		$progress['deliverylocation'] = $status->location;
		$progress['deliverydate'] = date("Y-m-d", $time);
		$progress['deliverytime'] = date("H:i", $time);
		$progress['activity'] = $status->name;
		return $progress;
	}



	public function getAllowedMethods()
	{
			$methods = array();
			$shippingMethods = $this->unifaunMysql4ShippingMethodCollectionFactory->create();
			foreach ($shippingMethods as $shippingMethod) {
					$code = $shippingMethod->getShippingmethodId();
					$methods[$code] = $shippingMethod->getTitle();
			}
			return $methods;
	}

}