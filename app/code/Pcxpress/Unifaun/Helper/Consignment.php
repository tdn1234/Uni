<?php
namespace Pcxpress\Unifaun\Helper;

class Consignment extends \Magento\Framework\App\Helper\AbstractHelper
{
	const LABEL_TYPE_ID = 1;
	const RECEIPT_TYPE_ID = 4;

	protected $fileNames = array(
		1 => 'unifaun-labels',
		4 => 'unifaun-receipts'
	);

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $salesOrderFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Backend\Helper\Data
     */
    protected $backendHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Model\Order\Pdf\ProformaFactory
     */
    protected $unifaunOrderPdfProformaFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Pcxpress\Unifaun\Model\ParcelFactory
     */
    protected $unifaunParcelFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment\Data
     */
    protected $unifaunPcxpressUnifaunConsignmentData;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory
     */
    protected $unifaunPcxpressUnifaunFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\SoapClientFactory
     */
    protected $unifaunPcxpressUnifaunSoapClientFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Magento\Sales\Model\OrderFactory $salesOrderFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Model\Order\Pdf\ProformaFactory $unifaunOrderPdfProformaFactory,
        \Psr\Log\LoggerInterface $logger,
        \Pcxpress\Unifaun\Model\ParcelFactory $unifaunParcelFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment\Data $unifaunPcxpressUnifaunConsignmentData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory $unifaunPcxpressUnifaunFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\SoapClientFactory $unifaunPcxpressUnifaunSoapClientFactory
    ) {
        $this->unifaunPcxpressUnifaunFactory = $unifaunPcxpressUnifaunFactory;
        $this->unifaunPcxpressUnifaunSoapClientFactory = $unifaunPcxpressUnifaunSoapClientFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->salesOrderFactory = $salesOrderFactory;
        $this->backendSession = $backendSession;
        $this->backendHelper = $backendHelper;
        $this->scopeConfig = $scopeConfig;
        $this->unifaunOrderPdfProformaFactory = $unifaunOrderPdfProformaFactory;
        $this->logger = $logger;
        $this->unifaunParcelFactory = $unifaunParcelFactory;
        $this->transactionFactory = $transactionFactory;
        $this->eventManager = $eventManager;
        $this->unifaunPcxpressUnifaunConsignmentData = $unifaunPcxpressUnifaunConsignmentData;
        $this->storeManager = $storeManager;
        parent::__construct(
            $context
        );
    }


	public function getFileNameByType($type) {
		return (isset($this->fileNames[$type]))? $this->fileNames[$type] : '';
	}

	public function printConsignment($request, $type = 1) {
		$document = null;
		

		$filename = $this->getFileNameByType($type);


		$consignmentNos = $request->getParam('consignment_nos');


		$orderIds = $request->getParam('orderIds');

		$coreHelper = Mage::helper('core');

		$useProforma = $this->unifaunHelper->getUseProforma();



		if (is_string($consignmentNos)) {

			$consignmentNos = array($consignmentNos);

		}



		$unifaunConsignmentDocumentsString = null;

		if (is_array($consignmentNos) && count($consignmentNos)) {

			$unifaun = $this->unifaunPcxpressUnifaunFactory->create();

			$document = \Zend_Pdf::parse(base64_decode($this->getConsignmentDocument($consignmentNos, $type)));

		}



		if (!$document) {

			$document = new \Zend_Pdf();

			$document->pages[] = new \Zend_Pdf_Page(\Zend_Pdf_Page::SIZE_A4);

		}



		if (!is_array($orderIds)) {

			$orderIds = explode(",", $orderIds);

		}



        // When Pro Forma Invoice is activated in config...

		if ($useProforma) {

			if (is_array($orderIds) && count($orderIds)) {

				foreach ($orderIds as $orderId) {

					$order = $this->salesOrderFactory->create()->load($orderId);

					if ($order) {



						$billingAddressCountryId = $order->getBillingAddress()->getCountryId();

						if (!$coreHelper->isCountryInEU($billingAddressCountryId, $order->getStoreId())) {

							$invoices = $order->getInvoiceCollection();

							if ($invoices->getSize() == 0) {

								$this->backendSession->addError(__('Order %s must have state "%s" before printing Pro Forma Invoice.', $orderId, ucfirst(\Magento\Sales\Model\Order::STATE_COMPLETE)));

								Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order/index"));

								return;

							}



							$sellerName = $this->scopeConfig->getValue('general/store_information/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());

							$sellerAddress = $this->scopeConfig->getValue('general/store_information/address', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());

							$sellerCountry = $this->scopeConfig->getValue('general/store_information/merchant_country', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());

							$sellerVat = $this->scopeConfig->getValue('general/store_information/merchant_vat_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStoreId());



							if (!$sellerName || !$sellerAddress || !$sellerCountry) {

								$this->backendSession->addError(__('Merchant address is not configured properly, this is required to print Pro Forma Invoice.'));

								Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order/index"));

								return;

							}



							if (!$sellerVat) {

								$this->backendSession->addError(__('Merchant VAT number is missing, this is required to print Pro Forma Invoice.'));

								Mage::app()->getResponse()->setRedirect($this->backendHelper->getUrl("adminhtml/sales_order/index"));

								return;

							}



							$proformaPdf = $this->unifaunOrderPdfProformaFactory->create()->getPdf($invoices);



							$document->pages = array_merge($document->pages, $proformaPdf->pages);

							$filename = 'unifaun-labels-and-proforma-invoices';

						}

					}

				}

			}

		}

		return array($filename, $document);

	}



	public function getConsignmentDocument($consignmentNos, $type=1)



	{
		// var_dump($consignmentNos);die;
		// $consignmentNos = array('6314103448');



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



		$arguments->type = $type;



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



		$tracks = $shipment->getTracksCollection();



		$consignmentNo = '';

		$track_number = '';

		foreach ($tracks as $track) {



			if($track->getData('track_number')){



				return $track->getData('track_number');



			}



		}



		return $track_number;



	}







	public function getConsignmentServiceUrl($consignment_no){



		$groupname = $this->scopeConfig->getValue('carriers/unifaun/groupname', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);



		return 'https://service.web-ta.net/public/track/#/cno/'. $consignment_no .'/by/' . $groupname ;		



	}


	public function bookConsignment($order_id) {
		// $order_id = '423';
		$order = $this->salesOrderFactory->create()->load($order_id);

		$this->createShipment($order);

		// var_dump($shipment->getData());die;
		// foreach ($order->getShipmentsCollection() as $shipment){
		// 	var_dump($shipment->getId());
		// }
		// var_dump($order_id);die;
	}

	public function getParcelData($order, $shipment) {

		$parcel = $this->unifaunParcelFactory->create()->setEntity($shipment)->getParcels();
		$shippingMethod = $method = explode("_", $order->getShippingMethod());
		$parcel[0]['shippingMethod'] = (array_key_exists(1, $shippingMethod))? $shippingMethod[1] : '';
		$parcel[0]['advice'] = $this->unifaunHelper->getAdviceType();
		return $parcel;
	}

	public function createShipment($order) {

		$qty = array();
		foreach($order->getAllItems() as $eachOrderItem){			
			$Itemqty=0;
			$Itemqty = $eachOrderItem->getQtyOrdered()
			- $eachOrderItem->getQtyShipped()
			- $eachOrderItem->getQtyRefunded()
			- $eachOrderItem->getQtyCanceled();
			$qty[$eachOrderItem->getId()] = $Itemqty;			
		}
		
		
		/* check order shipment is prossiable or not */

		$email = true;
		$includeComment = true;
		$comment = "";

		// var_dump($order->canShip());die;

		if ($order->canShip()) {
			/* @var $shipment Mage_Sales_Model_Order_Shipment */
			/* prepare to create shipment */			
			$shipment = $order->prepareShipment($qty);
			$this->setConsignmentData($order, $shipment);
			
			if ($shipment) {
				$shipment->register();
				// $shipment->addComment($comment, $email && $includeComment);
				$shipment->getOrder()->setIsInProcess(true);
				
				try {
					$transactionSave = $this->transactionFactory->create()
					->addObject($shipment)
					->addObject($shipment->getOrder())
					->save();

					// $this->getParcelData($order);

					// $consignmentData = Mage::getSingleton('unifaun/pcxpress_unifaun_consignment_data');

					// $consignmentData->setData('order_id', $order_id);
					// $consignmentData->setData('tdn123', 'tdnnnnnnn');

					

					return $shipment;
					// $shipment->sendEmail($email, ($includeComment ? $comment : ''));
				} catch (Mage_Core_Exception $e) {
					var_dump($e);
				}
				
			}
			
		}else{
			foreach ($order->getShipmentsCollection() as $shipment){
				// $shipment = $observer->getShipment();
				$this->setConsignmentData($order, $shipment);
				$this->eventManager->dispatch('sales_order_custom_shipment_save_before', array('shipment' => $shipment));
				return;
			}			
		}
		die('reee');
		return false;
	}

	public function setConsignmentData($order, $shipment)
	{
		$consignmentData = $this->unifaunPcxpressUnifaunConsignmentData;
		$parcel = $this->getParcelData($order, $shipment);
		$shippingData = $order->getShippingAddress();
		
		$consignmentData->setData('custom_booking', true);

		$consignmentData->setParcel($parcel);
		$params = array(
			'unifaun_automatic_booking' => 'Y',
			'unifaun_order_number' => $order->getIncrementId(),
			'unifaun_consignee_reference' => '',
			'unifaun_advice_contact' => $shippingData->getData('firstname') . ' ' . $shippingData->getData('lastname'),
			'unifaun_advice_mobile' => $shippingData->getData('telephone'),
			'unifaun_advice_email' => $shippingData->getData('email'),
			'unifaun_advice_fax' => $shippingData->getData('fax'),
			'unifaun_advice_phone' => $shippingData->getData('telephone'),
			'unifaun_cod' => 'N',
			'unifaun_cod_amount' => $order->getGrandTotal(),
			'unifaun_cod_currency' => $this->storeManager->getStore()->getCurrentCurrencyCode(),
			'unifaun_cod_reference' => $order->getIncrementId(),
			'unifaun_cod_paymentmethod' => 'CASH',
			'unifaun_cod_accountno' => '',
			'unifaun_pickup_location' => '',
			'unifaun_pickup' => 'N',
			'unifaun_pickup_date' => date('Y-m-d'),
			'unifaun_pickup_earliest' => '9:00',
			'unifaun_pickup_latest' => '15:00',
		);
		$consignmentData->setParams($params);

		// var_dump($consignmentData->getData());die;
	}

	public function generateToken($consignemtId = '', $orderId = '') {
		return substr(md5(trim($consignemtId) . trim(md5($orderId))), 2, 20);
	}
}