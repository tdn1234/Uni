<?php
namespace Pcxpress\Unifaun\Controller;


class IndexController extends \Magento\Framework\App\Action\Action
{
	protected static $_templateId = 2;

    /**
     * @var \Pcxpress\Unifaun\Helper\Consignment
     */
    protected $unifaunConsignmentHelper;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $salesOrderFactory;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerCustomerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    protected $storeAppEmulation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilderFactory
     */
    protected $templateTransportBuilderFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Mail\MessageInterfaceFactory
     */
    protected $messageInterfaceFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Pcxpress\Unifaun\Helper\Consignment $unifaunConsignmentHelper,
        \Magento\Sales\Model\OrderFactory $salesOrderFactory,
        \Magento\Customer\Model\CustomerFactory $customerCustomerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\App\Emulation $storeAppEmulation,
        \Magento\Framework\Mail\Template\TransportBuilderFactory $templateTransportBuilderFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\MessageInterfaceFactory $messageInterfaceFactory
    ) {
        $this->unifaunConsignmentHelper = $unifaunConsignmentHelper;
        $this->salesOrderFactory = $salesOrderFactory;
        $this->customerCustomerFactory = $customerCustomerFactory;
        $this->storeManager = $storeManager;
        $this->storeAppEmulation = $storeAppEmulation;
        $this->templateTransportBuilderFactory = $templateTransportBuilderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->messageInterfaceFactory = $messageInterfaceFactory;
        parent::__construct(
            $context
        );
    }

	public function execute(){
		$request = $this->getRequest();
		$consignmentId = $request->getParam('consignment_id');
		$orderId = $request->getParam('order_id');
		$token = $request->getParam('token');
		$type = 1;
		$consignmentHelper = $this->unifaunConsignmentHelper;

		$validationToken = $consignmentHelper->generateToken($consignmentId, $orderId);

		if ($validationToken !== $token) {
			return;
		}
		
		$request->setParam('consignment_nos', array($consignmentId));


		$request->setParam('orderIds', array($orderId));

		$consignmentHelper = $this->unifaunConsignmentHelper;

		list($filename, $document) = $consignmentHelper->printConsignment($request, $type);

		$labelPath = 'media/consignments/' . $filename. $consignmentId .'.pdf';
		
		$document->save($labelPath);

		$this->sendEmail(Mage::getBaseUrl(\Magento\Store\Model\Store::URL_TYPE_WEB) . $labelPath);
	}

	public function sendEmail($tracking_url) {
		$request = $this->getRequest();
		
		$orderId = $request->getParam('order_id');

		
		$order = $this->salesOrderFactory->create()->load($orderId);
		
		
		
		$customer = $this->customerCustomerFactory->create()->load($order->getData('customer_id'));

		

		$storeId = $this->storeManager->getStore()->getStoreId();


		$appEmulation = $this->storeAppEmulation;
		$initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);

        // Stop store emulation process
		$appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

		$mailer = $this->templateTransportBuilderFactory->create();

		$templateId = self::$_templateId;

		$copyTo = $this->_getEmails(\Magento\Sales\Model\Order::XML_PATH_UPDATE_EMAIL_COPY_TO, $storeId);
		$copyMethod = $this->scopeConfig->getValue(\Magento\Sales\Model\Order::XML_PATH_UPDATE_EMAIL_COPY_METHOD, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);


        // Set all required params and send emails

		$mailer->setStoreId($storeId);
		$mailer->setTemplateId($templateId);
		$mailer->setTemplateParams(array(
			'consignment_label'   => $tracking_url
		));




		$mailer->setSender($this->scopeConfig->getValue(\Magento\Sales\Model\Order::XML_PATH_UPDATE_EMAIL_IDENTITY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId));

		/** @var $emailQueue Mage_Core_Model_Email_Queue */
		$emailQueue = Mage::getModel('core/email_queue');
		$emailQueue->setEntityId($order->getId())
		->setEntityType('order')
		->setEventType('consignment_label')
		// ->setEventType('new_order')
		;

		$emailInfo = $this->messageInterfaceFactory->create();
		$emailInfo->addTo($customer->getEmail(), $customer->getFirstName());

		$mailer->addEmailInfo($emailInfo);

		        // Email copies are sent as separated emails if their copy method is
        // 'copy' or a customer should not be notified

		

		if ($copyTo) {
			foreach ($copyTo as $email) {
				$emailInfo = $this->messageInterfaceFactory->create();
				$emailInfo->addTo($email, 'Owner');			
				$mailer->addEmailInfo($emailInfo);				
			}
		}



		$mailer->setQueue($emailQueue)->send();


		die('Sent to '.$customer->getEmail().'!');
	}

	protected function _getEmails($configPath, $storeId)
	{
		$data = $this->scopeConfig->getValue($configPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		if (!empty($data)) {
			return explode(',', $data);
		}
		return false;
	}
}
