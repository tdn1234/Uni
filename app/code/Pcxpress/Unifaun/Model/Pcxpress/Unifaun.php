<?php
namespace Pcxpress\Unifaun\Model\Pcxpress;




/**



 * @category   PC  xpressPCXpress AB



 * @package    Pcxpress_Unifaun



 * @copyright  Copyright (c) 2017 PCXpress AB



 * @author     PCXpress AB Developer <info@pcxpress.se>



 * @license    http://pcxpress.se/magento/license.txt



 */



class Unifaun



{		



	private $_username;



	private $_password;



	private $_groupName;







	const FILE_FORMAT = 'PDF';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\SoapClientFactory
     */
    protected $unifaunPcxpressUnifaunSoapClientFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ErrorFactory
     */
    protected $unifaunPcxpressUnifaunErrorFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\StatusResultFactory
     */
    protected $unifaunPcxpressUnifaunStatusResultFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Psr\Log\LoggerInterface $logger,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\SoapClientFactory $unifaunPcxpressUnifaunSoapClientFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ErrorFactory $unifaunPcxpressUnifaunErrorFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\StatusResultFactory $unifaunPcxpressUnifaunStatusResultFactory
    )



	{
        $this->unifaunPcxpressUnifaunSoapClientFactory = $unifaunPcxpressUnifaunSoapClientFactory;
        $this->unifaunPcxpressUnifaunErrorFactory = $unifaunPcxpressUnifaunErrorFactory;
        $this->unifaunPcxpressUnifaunStatusResultFactory = $unifaunPcxpressUnifaunStatusResultFactory;
        $this->scopeConfig = $scopeConfig;
        $this->unifaunHelper = $unifaunHelper;
        $this->logger = $logger;		



		if ($this->isDeveloper()) {			



			ini_set("soap.wsdl_cache_enabled", "0");



		}		



	}







	public function isDeveloper()



	{		



		return $this->scopeConfig->getValue('carriers/unifaun/development', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);	



	}







	public function getConsignmentSoap()



	{



		return $this->unifaunPcxpressUnifaunSoapClientFactory->create( 



			$this->_getConsignmentWsdl(), 



			array( 



				"cache_wsdl" => WSDL_CACHE_NONE, 



				"exceptions" => 1, 



				"trace" => 1



				) 



			);		



	}







	private function _getAuthenthicationToken()



	{



		$helper = $this->unifaunHelper;



		$token = new \stdClass();



		$token->userName = $helper->getUsername();



		$token->groupName = $helper->getGroupName();



		$token->password = $helper->getPassword();



		return $token;



	}







	/**



	 * Return URL to Consignment WSDL



	 * @return string



	 */



	protected function _getConsignmentWsdl()



	{



		if ($this->isDeveloper()) {



			return "https://service.web-ta.net:443/ws/services/ConsignmentWS?wsdl";



		} else {



			return "https://service.web-ta.net/ws/services/ConsignmentWS?wsdl";



		}



	}







	/**



	 * Return URL to Status WSDL



	 * @return string



	 */



	protected function _getStatusWsdl()



	{



		if ($this->isDeveloper()) {



			return "https://service.web-ta.net:443/ws/services/ConsignmentWS?wsdl";



		} else {



			return "https://service.web-ta.net/ws/services/StatusWS?wsdl";



		}



	}











	/**



	 * bookConsignment.



	 * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment $consignment



	 * @param string $transactionId



	 * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentResult



	 */



	public function bookConsignment(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment $consignment, $transactionId = null)



	{



		$soapClient = $this->getConsignmentSoap();







		$params = new \stdClass();



		$params->AuthenticationToken = $this->_getAuthenthicationToken();



		$params->Consignment = $consignment;



		if ($transactionId !== null) {



			$params->transactionId = $transactionId;



		}



		try {



			$response = $soapClient->book($params);
			// var_dump($soapClient->__getLastRequest());
			// var_dump($response->getResult());
			// die;
			// Mage::log($soapClient->__getLastRequest(), null, 'tdnsoap.log');

		} catch (SoapFault $e) {



			return $this->errorHandle($e);



		}







		return $response->getResult();



	}







	private function errorHandle($e){



		$this->logger->log(null, $e->getCode().": ".$e->getMessage());



		$error = $this->unifaunPcxpressUnifaunErrorFactory->create();



		$error->setDescription($e->getMessage());



		$error->setTrace($e->getTraceAsString());



		$error->setCode($e->getCode());



		return $error;



	}







	/**



	 * Save consignment. The ConsignmentResult contains the booked consignment or a list of errors



	 * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment $consignment



	 * @param string $transactionId



	 * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentResult



	 */



	public function saveConsignment(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment $consignment, $transactionId = null) {


		$soapClient = $this->getConsignmentSoap();

		$params = new \stdClass();



		$params->AuthenticationToken = $this->_getAuthenthicationToken();



		$params->Consignment = $consignment;



		if ($transactionId !== null) {



			$params->TransactionId = $transactionId;



		}


		try {

				

			$response = $soapClient->save($params);

			// Mage::log($soapClient->__getLastRequest(), null, 'tdnsoap.log');

			// var_dump($soapClient->__getLastRequest());
			



		} catch (SoapFault $e) {







			return $this->errorHandle($e);



		}



		



		return $response->getResult();



	}







	/**



	 * Track consignment



	 * @param int $consignmentNo



	 * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Error



	 */



	public function trackConsignment($consignmentNo) {



		$soapClient = $this->_getStatusSoapClient();



		$params = new \stdClass();



		$params->AuthenticationToken = $this->_getAuthenthicationToken();



		$params->consignmentNo = $consignmentNo;



		try {



			$response = $soapClient->findByConsignmentNo($params);


			// Mage::log($soapClient->__getLastRequest(), null, 'tdnsoap.log');




		} catch (SoapFault $e) {



			return $this->errorHandle($e);



		}







		$result = $response->getResult();



		



		if ($result instanceof \stdClass && isset($result->statusCode) && $result->statusCode == 0) {



			return $this->setResultData($result);







		} elseif (!$result instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\StatusResult) {



			return null;



		}







		return $response->getResult();



	}







	private function setResultData($result){



		$status = $this->unifaunPcxpressUnifaunStatusResultFactory->create();



		$status->setErrors(isset($result->errors) ? $result->errors : null);



		$status->setStatusCode(isset($result->statusCode) ? $result->statusCode : null);



		$status->setStatus(isset($result->status) ? $result->status : null);



		return $status;



	}



	



	/**



	 * getConsignmentStatusIsComplete



	 * @param string $username



	 * @param string $password



	 * @param string $groupName



	 */



	public function getConsignmentStatusIsComplete($consignmentId)
	{

		$isComplete = false;
		$soapClient = $this->getConsignmentSoap(); /** @var $soapClient Pcxpress_Unifaun_Model_Pcxpress_Unifaun_SoapClient */
		$arguments  = new \stdClass();
		$arguments->AuthenticationToken = $this->_getAuthenthicationToken();
		$arguments->consignmentId = $consignmentId;
		try {
			$response = $soapClient->__soapCall('isComplete', array($arguments));
			if ($response->result) {
				$isComplete = $response->result;
			}
		} catch (SoapFault $e) {
			if ($e->getMessage() == 'ERR_CONSIGNMENT_NOT_FOUND') {
				return null;
			}else{
				return $this->errorHandle($e);
			}			
		}
		return $isComplete;
	}



	
	public function getConsignmentStatusIsBooked($consignmentId)
	{

		$isBooked = false;
		$soapClient = $this->getConsignmentSoap(); /** @var $soapClient Pcxpress_Unifaun_Model_Pcxpress_Unifaun_SoapClient */
		$arguments  = new \stdClass();
		$arguments->AuthenticationToken = $this->_getAuthenthicationToken();
		$arguments->consignmentId = $consignmentId;
		try {
			$response = $soapClient->__soapCall('isBooked', array($arguments));
			if ($response->result) {
				$isBooked = $response->result;
			}
		} catch (SoapFault $e) {
			if ($e->getMessage() == 'ERR_CONSIGNMENT_NOT_FOUND') {
				return null;
			}else{
				return $this->errorHandle($e);
			}			
		}
		return $isBooked;
	}


	private function _getStatusSoapClient()



	{







		return $this->unifaunPcxpressUnifaunSoapClientFactory->create( 



			$this->_getStatusWsdl(), 



			array( "cache_wsdl" => WSDL_CACHE_NONE, 



				"exceptions" => 1, 



				"trace" => 1) 



		);



		



	}



}