<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Helper;

class Consignee extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory
     */
    protected $unifaunPcxpressUnifaunAddressFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory $unifaunPcxpressUnifaunAddressFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->unifaunPcxpressUnifaunAddressFactory = $unifaunPcxpressUnifaunAddressFactory;
        parent::__construct(
            $context
        );
    }

	protected function _getConfigValue($field)
	{
		$value = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/' . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		return $value;
	}
	
	public function getConsigneeAddress($order, $returnedShipment) {
		$address = $order->getShippingAddress();

		$consigneeName = $address->getName();

		if ($address->getCompany()) {

			$consigneeName = $address->getCompany();

		}

		$addressConsignee = $this->unifaunPcxpressUnifaunAddressFactory->create();
		$addressConsignee->setId("-");

		if ($returnedShipment){
			return $this->getPcxpressAddress($addressConsignee);
		}

		
		$addressLines = array($address->getStreet1());
		if($address->getStreet2()!=''){

			$addressLines[] = $address->getStreet2();

		}

		if($address->getStreet3()!=''){
			$addressLines[] = $address->getStreet3();
		}

		$addressConsignee->setName($consigneeName);
		$addressConsignee->setAddress($addressLines);
		$addressConsignee->setCity($address->getCity());
		$addressConsignee->setPostCode($address->getPostcode());
		$addressConsignee->setState($address->getRegionCode()); // getRegionCode() - Return 2 letter state code if available, otherwise full region name
		$addressConsignee->setCountryCode($address->getCountryId());
		return $addressConsignee;
	}

	private function getPcxpressAddress($addressConsignee){
		$addressConsignee->setName($this->_getConfigValue("consignor_name"));
		$addressConsignee->setAddress($this->_getConfigValue("consignor_address"));
		$addressConsignee->setCity($this->_getConfigValue("consignor_city"));
		$addressConsignee->setPostCode($this->_getConfigValue("consignor_postcode"));
		$addressConsignee->setState($this->_getConfigValue("consignor_state"));
		$addressConsignee->setCountryCode($this->_getConfigValue("consignor_countrycode"));
		return $addressConsignee;
	}
}