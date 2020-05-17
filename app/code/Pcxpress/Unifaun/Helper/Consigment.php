<?php
namespace Pcxpress\Unifaun\Helper;


class Consignment extends \Magento\Framework\App\Helper\AbstractHelper

{

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\SoapClientFactory
     */
    protected $unifaunPcxpressUnifaunSoapClientFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\SoapClientFactory $unifaunPcxpressUnifaunSoapClientFactory
    ) {
        $this->unifaunPcxpressUnifaunSoapClientFactory = $unifaunPcxpressUnifaunSoapClientFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        parent::__construct(
            $context
        );
    }


	public function getConsignmentDocument($consignmentNos)

	{

		if (!is_array($consignmentNos)) {

			$consignmentNos = array($consignmentNos);

		}

		$soapClient = $this->getConsignmentSoap();

		$arguments = new \stdClass();



		$helper = $this->unifaunHelper;

		$token = new \stdClass();

		$token->userName = $helper->getUsername();

		$token->groupName = $helper->getGroupName();

		$token->password = $helper->getPassword();



		$arguments->AuthenticationToken = $token;

		$arguments->arrayOfConsignmentNo = $consignmentNos;

		$arguments->type = 1;

		$arguments->format = 'PDF';	







		try {

			$response = $soapClient->__soapCall('print', array($arguments));

			if ($response->result->documents) {

				if (is_array($response->result->documents)) {

					if (count($response->result->documents) == 0) {

						$data = null;

					} elseif (count($response->result->documents) > 1) {

						$pdf = new \Zend_Pdf();

						foreach ($response->result->documents as $doc) {

							$pdfDoc = \Zend_Pdf::parse(base64_decode($doc->data));

							foreach ($pdfDoc->pages as $page) {

								$pdf->pages[] = clone $page;

							}

						}

						$data = base64_encode($pdf->render());

					} else {

						$data = $response->result->documents[0]->data;

					}

				} else {

					$data = $response->result->documents->data;

				}				

				return $data;

			} else {

				return null;

			}

		} catch (SoapFault $e) {	

			$this->logger->log(null, $e->getCode().": ".$e->getMessage());

		}

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



	protected function _getConsignmentWsdl()

	{

		if ($this->scopeConfig->getValue('carriers/unifaun/development', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {

			return "https://service.web-ta.net:443/ws/services/ConsignmentWS?wsdl";

		} else {

			return "https://service.web-ta.net/ws/services/ConsignmentWS?wsdl";

		}

	}



	public function getConsignmentFromShipment($shipment){
			var_dump('testst');die;
		$tracks = $this->getShipment()->getTracksCollection();

		$consignmentNo = '';
		$track_number = '';

		foreach ($tracks as $track) {

		    if($track->getData('track_number')){

		    	return $track->getData('track_number');

		    }

		}

		return $track_number;

	}

}