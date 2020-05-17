<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http=>//pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Helper\Pacsoft;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

	const PACSFOT_API_ENDPOINT = 'https://api.unifaun.com/rs-extapi/v1/';

	const PACSOFT_PAYMENT_METHOD = 7;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Backend\Model\Session $backendSession,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->backendSession = $backendSession;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        parent::__construct(
            $context
        );
    }


	public function createStoredShipment($params, $transactionId, $parcels, $order)	
	{
		$data = $this->setupData($params, $transactionId, $parcels, $order);

		return $this->send($data, "stored-shipments");

		// var_dump(json_decode($response));die;
		// var_dump($response);
		// die('stored-shipments');

	}

	public function createNormalShipment($params, $transactionId, $parcels, $order) {

		$data = $this->setupData($params, $transactionId, $parcels, $order);

		$storedData = [
			"printConfig"=> [
				"target1Media"=> "thermo-250",
				"target1Type"=> "zpl",
				"target1YOffset"=> 0,
				"target1XOffset"=> 0,
				"target1Options"=> [
					["key"=> "mode", "value"=> "DT"]
				],
				"target2Media"=> "laser-a4",
				"target2Type"=> "pdf",
				"target2YOffset"=> 0,
				"target2XOffset"=> 0,
				"target3Media"=> null,
				"target3Type"=> "pdf",
				"target3YOffset"=> 0,
				"target3XOffset"=> 0,
				"target4Media"=> null,
				"target4Type"=> "pdf",
				"target4YOffset"=> 0,
				"target4XOffset"=> 0
			],
			'shipment' => $data				
		];
		

		return $this->send($storedData, 'shipments');
	 

	}

	public function createShipment($params, $transactionId, $parcels, $order) {

		try {

			$storeShipment = (isset($params['unifaun_automatic_booking']) && $params['unifaun_automatic_booking'] == 'N')? true : false;

			if ($storeShipment) {
				$response = $this->createStoredShipment($params, $transactionId, $parcels, $order);
			} else {
				$response = $this->createNormalShipment($params, $transactionId, $parcels, $order);

			}
			$response = json_decode($response, true);
			
			if (isset($response['statuses'])) {
				$status = reset($response['statuses']);
				    $this->backendSession->addError($status['message']);				
			}
		} catch (Exception $e) {
			$this->logger->critical($e->getMessage());
			// var_dump($e->getMessage());die;
		}		

	}

	protected function setupData($params, $transactionId, $parcels, $order)
	{

		$recieverData = $order->getShippingAddress()->getData();

		
		$sender_phone = $this->scopeConfig->getValue('carriers/unifaun/phone', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$sender_email = $this->scopeConfig->getValue('contacts/email/recipient_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);		

		$quickId = $transactionId;

		$sender_name = $this->scopeConfig->getValue('carriers/unifaun/consignor_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$sender_address1 = $this->scopeConfig->getValue('carriers/unifaun/consignor_address', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

		$sender_city = $this->scopeConfig->getValue('carriers/unifaun/consignor_city', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

		$sender_postcode = $this->scopeConfig->getValue('carriers/unifaun/consignor_postcode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

		$sender_country = $this->scopeConfig->getValue('carriers/unifaun/consignor_countrycode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		

		$pacsoft_id = $this->scopeConfig->getValue('carriers/unifaun/pacsoft_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$parcelsData = [];

		foreach ($parcels as $parcel) {
			$parcelData = array(
				"copies"=> "1",
				"weight"=> $parcel['weight'],
				"contents"=> $parcel['advice'],
				"valuePerParcel"=> true
			);
			$parcelsData[] = $parcelData;
		}
		
		$templateMethod = $order->getData('template_method_data');

		$advice = '';
		if ($templateMethod['advice_default'] == 'phone') {
			$advice = 'NOTSMS';$advice = 'NOTSMS';
		} elseif ($templateMethod['advice_default'] == 'email') {
			$advice = 'NOTEMAIL';		
		} else {
			$advice = 'NOT';
		}
		
		

		$data = [
			"sender"=> [
				"quickId"=> 1,
				"name"=> $sender_name,
				"address1"=> $sender_address1,				
				"zipcode"=> $sender_postcode,
				"city"=> $sender_city,
				"country"=> $sender_country,
				"phone"=> $sender_phone,
				"email"=> $sender_email
			],
			"receiver"=> [
				"name"=> $recieverData['firstname']. ' ' . $recieverData['lastname'],
				"address1"=> $recieverData['street'],
				"zipcode"=> $recieverData['postcode'],	
				// "zipcode" => '11359',	
				"city"=> $recieverData['city'],
				"country"=> $recieverData['country_id'],
				"mobile"=> $recieverData['telephone'],
				"email"=> $order->getEmail()
			],
			"service"=> [
				"id"=> $templateMethod['template_name'],
				'addons' => [
					['id' => $advice],
					['id' => 'RPAY', 'custNo' => '12345674']
				]
			],
			"parcels"=> $parcelsData,
			"orderNo"=> $params['unifaun_order_number'],
			"senderReference"=> "ref",			
			"receiverReference"=> "ref",
			// "options"=> [[
			// 	"message"=> "This is order number 123",
			// 	"to"=> "email2@example.com",
			// 	"id"=> "ENOT",
			// 	"languageCode"=> "SE",
			// 	"from"=> "email1@example.com"
			// ]]
		];

		// var_dump($data);
		
		// var_dump(json_encode($data));die;
		return $data;
	}

	public function send($data, $endpoint)
	{
		// echo json_encode($data);die;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => self::PACSFOT_API_ENDPOINT . $endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				"authorization: Basic UUhTM0E1UUs3UVI3QjZBRTpJS09IUFY2SUZLNEVHWjJYQ1VKV1pDT1c=",
				"cache-control: no-cache",
				"content-type: application/json",
				"postman-token: b7bd886b-704b-a4c7-e202-e4c96d975169"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		return $response;
	}
}