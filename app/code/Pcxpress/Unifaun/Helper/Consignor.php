<?php
namespace Pcxpress\Unifaun\Helper;


/**

 * @category   PC  xpressPCXpress AB

 * @package    Pcxpress_Unifaun

 * @copyright  Copyright (c) 2017 PCXpress AB

 * @author     PCXpress AB Developer <info@pcxpress.se>

 * @license    http://pcxpress.se/magento/license.txt

 */

class Consignor extends \Magento\Framework\App\Helper\AbstractHelper

{





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
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory
     */
    protected $unifaunPcxpressUnifaunCommunicationFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PartFactory $unifaunPcxpressUnifaunPartFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory $unifaunPcxpressUnifaunAddressFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory $unifaunPcxpressUnifaunCommunicationFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->unifaunPcxpressUnifaunPartFactory = $unifaunPcxpressUnifaunPartFactory;
        $this->unifaunPcxpressUnifaunAddressFactory = $unifaunPcxpressUnifaunAddressFactory;
        $this->unifaunPcxpressUnifaunCommunicationFactory = $unifaunPcxpressUnifaunCommunicationFactory;
        parent::__construct(
            $context
        );
    }
	



	protected function _getConfigValue($field)

	{

		$value = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/' . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

		return $value;

	}







	public function getConsignorPart($order, $returnedShipment)

	{		

		$orderAddress = $order->getShippingAddress();

		$address = $this->getAddress($orderAddress, $returnedShipment);



		$communication = $this->getCommunication($orderAddress, $returnedShipment);

		$part = $this->unifaunPcxpressUnifaunPartFactory->create();

		$part->setAddress($address);

		$part->setCommunication($communication);

		$part->setRole("consignor");



		return $part;

	}



	public function getAddress($orderAddress, $returnedShipment){





		$address = $this->unifaunPcxpressUnifaunAddressFactory->create();

		$address->setId("-");

		//client wants return products to Pcxpress

		if ($returnedShipment){

			return $this->getAddressFromOrder($orderAddress, $address);		

		}

		$address->setName($this->_getConfigValue("consignor_name"));

		$address->setAddress($this->_getConfigValue("consignor_address"));

		$address->setCity($this->_getConfigValue("consignor_city"));

		$address->setPostCode($this->_getConfigValue("consignor_postcode"));

		$address->setState($this->_getConfigValue("consignor_state"));

		$address->setCountryCode($this->_getConfigValue("consignor_countrycode"));



		return $address;

	}



	private function getAddressFromOrder($orderAddress, $address){
	

		$consigneeName = $orderAddress->getName();



		if ($orderAddress->getCompany()) {



			$consigneeName = $orderAddress->getCompany();



		}



		$address = $this->unifaunPcxpressUnifaunAddressFactory->create();

		$address->setId("-");

		

		$addressLines = array($orderAddress->getStreet1());

		if($orderAddress->getStreet2()!=''){



			$addressLines[] = $orderAddress->getStreet2();



		}



		if($orderAddress->getStreet3()!=''){

			$addressLines[] = $orderAddress->getStreet3();

		}



		$address->setName($consigneeName);

		$address->setAddress($addressLines);

		$address->setCity($orderAddress->getCity());

		$address->setPostCode($orderAddress->getPostcode());

		$address->setState($orderAddress->getRegionCode()); // getRegionCode() - Return 2 letter state code if available, otherwise full region name

		$address->setCountryCode($orderAddress->getCountryId());

		return $address;

	}



	



	public function getCommunication($orderAddress, $returnedShipment){

		$communication = $this->unifaunPcxpressUnifaunCommunicationFactory->create();

		if ($returnedShipment) {
			$communication->setContactPerson($orderAddress->getName());

			$communication->setPhone($orderAddress->getTelephone());

			$communication->setEmail($orderAddress->getEmail());
		} else {
			$communication->setContactPerson($this->_getConfigValue("contact_person"));

			$communication->setPhone($this->_getConfigValue("phone"));

			$communication->setEmail($this->_getConfigValue("email"));
		}
		

		return $communication;

	}



}