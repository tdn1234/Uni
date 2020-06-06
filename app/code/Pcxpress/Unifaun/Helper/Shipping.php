<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Helper;

class Shipping extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_shippingMethodModels = [
		'weight' => 'unifaun/carrier_calculationMethod_weight',
		'unit' => 'unifaun/carrier_calculationMethod_unit'
	];

	const WEIGHT_CALCULATION_METHOD = 'weight';
	const UNIT_CALCULATION_METHOD = 'unit';

	public static $schenkerTemplateName = "schenker";
	public static $mypackTemplateName = "mypack";

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PartFactory
     */
    protected $unifaunPcxpressUnifaunPartFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory
     */
    protected $unifaunPcxpressUnifaunAddressFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProductFactory
     */
    protected $unifaunPcxpressUnifaunTransportProductFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory
     */
    protected $unifaunPcxpressUnifaunCommunicationFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CodFactory
     */
    protected $unifaunPcxpressUnifaunCodFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PickupFactory
     */
    protected $unifaunPcxpressUnifaunPickupFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PartFactory $unifaunPcxpressUnifaunPartFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory $unifaunPcxpressUnifaunAddressFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProductFactory $unifaunPcxpressUnifaunTransportProductFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory $unifaunPcxpressUnifaunCommunicationFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CodFactory $unifaunPcxpressUnifaunCodFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PickupFactory $unifaunPcxpressUnifaunPickupFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->unifaunPcxpressUnifaunPartFactory = $unifaunPcxpressUnifaunPartFactory;
        $this->unifaunPcxpressUnifaunAddressFactory = $unifaunPcxpressUnifaunAddressFactory;
        $this->unifaunPcxpressUnifaunTransportProductFactory = $unifaunPcxpressUnifaunTransportProductFactory;
        $this->unifaunPcxpressUnifaunCommunicationFactory = $unifaunPcxpressUnifaunCommunicationFactory;
        $this->unifaunPcxpressUnifaunCodFactory = $unifaunPcxpressUnifaunCodFactory;
        $this->unifaunPcxpressUnifaunPickupFactory = $unifaunPcxpressUnifaunPickupFactory;
        parent::__construct(
            $context
        );
    }
	

		protected function _getConfigValue($field)
	{
		$value = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/' . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->_scopeId);
		return $value;
	}

	public function getCalculationMethod($method = 'unit'){
		return $this->_shippingMethodModels[$method];
	}

	public function getConsignorPart()
	{
		$address = $this->getAddress();

		$communication = $this->getCommunication();
		$part = $this->unifaunPcxpressUnifaunPartFactory->create();
		$part->setAddress($address);
		$part->setCommunication($communication);
		$part->setRole("consignor");

		return $part;
	}

	public function getAddress(){
		$address = $this->unifaunPcxpressUnifaunAddressFactory->create();
		$address->setId("-");
		$address->setName($this->_getConfigValue("consignor_name"));
		$address->setAddress($this->_getConfigValue("consignor_address"));
		$address->setCity($this->_getConfigValue("consignor_city"));
		$address->setPostCode($this->_getConfigValue("consignor_postcode"));
		$address->setState($this->_getConfigValue("consignor_state"));
		$address->setCountryCode($this->_getConfigValue("consignor_countrycode"));
		return $address;
	}

	public function getTransportProduct($settings, $shippingMethod, $order){
		$this->_scopeId = $order->getStoreId();
		$transportProduct = $this->unifaunPcxpressUnifaunTransportProductFactory->create();
		if (isset($settings['paymentInstruction']) && in_array($settings["paymentInstruction"], array("P", "C", "T"))) {
			$transportProduct->setPaymentInstruction($settings["paymentInstruction"]);
		} else {
			$transportProduct->setPaymentInstruction(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProduct::PAYMENTINSTRUCTION_PREPAID);
		}

		$transportProduct = $this->setTransportProductSettings($transportProduct, $settings, $shippingMethod);

		return $transportProduct;
	}

	public function getCommunication(){
		$communication = $this->unifaunPcxpressUnifaunCommunicationFactory->create();
		$communication->setContactPerson($this->_getConfigValue("contact_person"));
		$communication->setPhone($this->_getConfigValue("phone"));
		$communication->setEmail($this->_getConfigValue("email"));
		return $communication;
	}

	public function setTransportProductSettings($transportProduct, $settings, $shippingMethod){

		$transportProduct = $this->setAdvice($transportProduct, $settings, $shippingMethod);

		$transportProduct = $this->setCod($transportProduct, $settings);
		$transportProduct = $this->setPickupLocation($transportProduct, $settings);
	

		return $transportProduct;	
	}

	public function setAdvice($transportProduct, $settings, $shippingMethod){
		$advice = false;
		$adviceType = null;

		if (isset($settings['advice'])) {
			if(isset($settings['advice']['type'])){
				switch ($settings['advice']['type']) {
					case "mobile":
					$advice = true;
					$adviceType = \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::MOBILE;
					break;
					case "email":
					$advice = true;
					$adviceType = \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::EMAIL;
					break;
					case "phone":
					$advice = true;
					$adviceType = \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::PHONE;
					break;
					case "fax":
					$advice = true;
					$adviceType = \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::FAX;
					break;
					case "postal":
					$advice = true;
					$adviceType = null;
					break;
				}
			}
		}

		$shippingTemplate = $shippingMethod->getTemplateName();		
		if (stripos($shippingTemplate, self::$mypackTemplateName) !== false) {
			$transportProduct->setAdvice($advice);
		}

		$schenkerTemplateName = "schenker";
		if (stripos($shippingTemplate, self::$schenkerTemplateName) !== false && $adviceType == \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::MOBILE) {
			$transportProduct->setAdvice($advice);
		}
		// var_dump($adviceType);
		$transportProduct->setAdviceType($adviceType);

		return $transportProduct;
	}

	public function setCod($transportProduct, $settings){
		if (isset($settings['cod'])) {
			$cod = $this->unifaunPcxpressUnifaunCodFactory->create();
			$cod->setAmount($settings['cod']['amount']);
			$cod->setPaymentMethod($settings['cod']['paymentMethod']);
			$cod->setCurrency($settings['cod']['currency']);
			$cod->setAccountNo($settings['cod']['accountNo']);
			$cod->setReference($settings['cod']['reference']);
			$transportProduct->setCod($cod);
		}
		return $transportProduct;
	}	

	public function setPickupLocation($transportProduct, $settings){
		if (isset($settings['pickup'])) {
			$pickup = $this->unifaunPcxpressUnifaunPickupFactory->create();
			$pickup->setDate($settings['pickup']['date']);
			$pickup->setEarliest($settings['pickup']['earliest']);
			$pickup->setLatest($settings['pickup']['latest']);
			$pickup->setInstruction($settings['pickup']['instruction']);
			$pickup->setLocation($settings['pickup']['location']);
			$transportProduct->setPickup($pickup);
		}
		return $transportProduct;
	}
}