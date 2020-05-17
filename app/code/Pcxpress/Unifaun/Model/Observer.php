<?php
namespace Pcxpress\Unifaun\Model;








/**







 * @category   PC  xpressPCXpress AB







 * @package    Pcxpress_Unifaun







 * @copyright  Copyright (c) 2017 PCXpress AB







 * @author     PCXpress AB Developer <info@pcxpress.se>







 * @license    http://pcxpress.se/magento/license.txt







 */















class Observer
{







    /**







     * On shipment creat add new consignment 







     *







     * @param \Magento\Framework\Event\Observer $observer







     * @param \Magento\Framework\App\Request\Http $request







     * @throws \Exception







     * @return bool







     */




    const RETURN_CONSIGNMENT = 'return_consignment';
    protected $writeConnection;
    protected $shipment_track_table;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment\Data
     */
    protected $unifaunPcxpressUnifaunConsignmentData;

    /**
     * @var \Pcxpress\Unifaun\Model\ParcelFactory
     */
    protected $unifaunParcelFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Pacsoft\Data
     */
    protected $unifaunPacsoftDataHelper;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentFactory
     */
    protected $unifaunPcxpressUnifaunConsignmentFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory
     */
    protected $unifaunPcxpressUnifaunFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProductFactory
     */
    protected $unifaunPcxpressUnifaunTransportProductFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Shipping
     */
    protected $unifaunShippingHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\InsuranceFactory
     */
    protected $unifaunPcxpressUnifaunInsuranceFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Consignor
     */
    protected $unifaunConsignorHelper;

    /**
     * @var \Pcxpress\Unifaun\Helper\Consignee
     */
    protected $unifaunConsigneeHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory
     */
    protected $unifaunPcxpressUnifaunCommunicationFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PartFactory
     */
    protected $unifaunPcxpressUnifaunPartFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ReferenceFactory
     */
    protected $unifaunPcxpressUnifaunReferenceFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsItemFactory
     */
    protected $unifaunPcxpressUnifaunGoodsItemFactory;

    /**
     * @var \Magento\Sales\Model\Order\Shipment\TrackFactory
     */
    protected $salesOrderShipmentTrackFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\PickupLocationFactory
     */
    protected $unifaunPickupLocationFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory
     */
    protected $unifaunPcxpressUnifaunAddressFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\LabelFactory
     */
    protected $unifaunLabelFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\PackageConfigFactory
     */
    protected $unifaunPackageConfigFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CustomsClearanceFactory
     */
    protected $unifaunPcxpressUnifaunCustomsClearanceFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\Request\Http $request,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment\Data $unifaunPcxpressUnifaunConsignmentData,
        \Pcxpress\Unifaun\Model\ParcelFactory $unifaunParcelFactory,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory,
        \Pcxpress\Unifaun\Helper\Pacsoft\Data $unifaunPacsoftDataHelper,
        \Magento\Backend\Model\Session $backendSession,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentFactory $unifaunPcxpressUnifaunConsignmentFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory $unifaunPcxpressUnifaunFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProductFactory $unifaunPcxpressUnifaunTransportProductFactory,
        \Pcxpress\Unifaun\Helper\Shipping $unifaunShippingHelper,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\InsuranceFactory $unifaunPcxpressUnifaunInsuranceFactory,
        \Pcxpress\Unifaun\Helper\Consignor $unifaunConsignorHelper,
        \Pcxpress\Unifaun\Helper\Consignee $unifaunConsigneeHelper,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory $unifaunPcxpressUnifaunCommunicationFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PartFactory $unifaunPcxpressUnifaunPartFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ReferenceFactory $unifaunPcxpressUnifaunReferenceFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsItemFactory $unifaunPcxpressUnifaunGoodsItemFactory,
        \Magento\Sales\Model\Order\Shipment\TrackFactory $salesOrderShipmentTrackFactory,
        \Pcxpress\Unifaun\Model\PickupLocationFactory $unifaunPickupLocationFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory $unifaunPcxpressUnifaunAddressFactory,
        \Pcxpress\Unifaun\Model\LabelFactory $unifaunLabelFactory,
        \Pcxpress\Unifaun\Model\PackageConfigFactory $unifaunPackageConfigFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CustomsClearanceFactory $unifaunPcxpressUnifaunCustomsClearanceFactory
    )
    {
        $this->unifaunPcxpressUnifaunCustomsClearanceFactory = $unifaunPcxpressUnifaunCustomsClearanceFactory;
        $this->resourceConnection = $resourceConnection;
        $this->request = $request;
        $this->unifaunPcxpressUnifaunConsignmentData = $unifaunPcxpressUnifaunConsignmentData;
        $this->unifaunParcelFactory = $unifaunParcelFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
        $this->unifaunPacsoftDataHelper = $unifaunPacsoftDataHelper;
        $this->backendSession = $backendSession;
        $this->unifaunPcxpressUnifaunConsignmentFactory = $unifaunPcxpressUnifaunConsignmentFactory;
        $this->unifaunPcxpressUnifaunFactory = $unifaunPcxpressUnifaunFactory;
        $this->unifaunPcxpressUnifaunTransportProductFactory = $unifaunPcxpressUnifaunTransportProductFactory;
        $this->unifaunShippingHelper = $unifaunShippingHelper;
        $this->unifaunPcxpressUnifaunInsuranceFactory = $unifaunPcxpressUnifaunInsuranceFactory;
        $this->unifaunConsignorHelper = $unifaunConsignorHelper;
        $this->unifaunConsigneeHelper = $unifaunConsigneeHelper;
        $this->unifaunPcxpressUnifaunCommunicationFactory = $unifaunPcxpressUnifaunCommunicationFactory;
        $this->scopeConfig = $scopeConfig;
        $this->unifaunPcxpressUnifaunPartFactory = $unifaunPcxpressUnifaunPartFactory;
        $this->unifaunPcxpressUnifaunReferenceFactory = $unifaunPcxpressUnifaunReferenceFactory;
        $this->unifaunPcxpressUnifaunGoodsItemFactory = $unifaunPcxpressUnifaunGoodsItemFactory;
        $this->salesOrderShipmentTrackFactory = $salesOrderShipmentTrackFactory;
        $this->unifaunPickupLocationFactory = $unifaunPickupLocationFactory;
        $this->unifaunPcxpressUnifaunAddressFactory = $unifaunPcxpressUnifaunAddressFactory;
        $this->unifaunLabelFactory = $unifaunLabelFactory;
        $this->unifaunPackageConfigFactory = $unifaunPackageConfigFactory;

        $resource = $this->resourceConnection;

        $this->writeConnection = $resource->getConnection('core_write');

        $this->shipment_track_table = $resource->getTableName('sales_flat_shipment_track');
            
    }


    public function addConsignment(\Magento\Framework\Event\Observer $observer, \Magento\Framework\App\Request\Http $request = null)
    {

        // $this->updateTrackingCode('153', '555553464356');

        // die('up');

        // Mage::log('$soapClient->__getLastRequest()', null, 'tdnsoap.log');

        // var_dump(Mage::app()->getRequest()->getParams());die;

        // var_dump(Mage::app()->getRequest()->getParam('parcel'));die;

        // var_dump(Mage::app()->getRequest()->getParam('parcel'));die;


        $returnedShipment = ($this->request->getParam('submit') === self::RETURN_CONSIGNMENT)? true : false;


        $shipment = $observer->getShipment();


        $consignmentData = $this->unifaunPcxpressUnifaunConsignmentData;



        // var_dump(get_class_methods($consignmentData));

	        // var_dump(Mage::app()->getRequest()->getParam('parcel'));die;

	        // var_dump($consignmentData->getData());die;

        // if (!count($consignment->getData())) {

        // }


        $parcels = null;

        $params = false;

        if($consignmentData->getData('custom_booking')){            
            // die('cccc');
            $parcels = $consignmentData->getData('parcel');

            $params =  $consignmentData->getData('params');
        }else if (!$shipment->isObjectNew()) {            
            $parcels = $this->request->getParam('parcel');
            // return false;
        }else{
            $parcels = $this->request->getParam('parcel');


            $params =  $this->request->getParams();
        }

        
        

        if ($params === false){
            $params =  $this->request->getParams();
        }

        if ($parcels == null) {
            $parcels = $this->unifaunParcelFactory->create()->setEntity($shipment)->getParcels();

            if (!count($parcels)) {
                $parcels[] = array('weight' => $this->_getShipmentWeight($shipment));
            }
        } elseif (!is_array($parcels)) {
            throw new \Exception("Parcels must be an array collection.");
        }


        $packages = $this->_getPackagesByMethodAndAdvice($parcels);
        $order = $shipment->getOrder();

        $this->updateOrderShipment($order);





        if (!$order->getId()) {
            throw new \Exception('Order not found.');
        }









        $carrierCode = $order->getShippingCarrier()->getCarrierCode();







        $failedPackages = array();



        if(!$carrierCode){
            $carrierCode = $order->getShippingMethod();
        }



        if (strpos($carrierCode, \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE) !== false) {
            foreach ($packages as $key => $_package) {
                $methodId = null;



                if ($this->unifaunHelper->isTemplateChangeEnabled() && array_key_exists('method', $_package)) {
                    $methodId = $_package['method'];
                }







                if (!$methodId) {
                    $method = explode("_", $order->getShippingMethod());







                    if (!array_key_exists(1, $method)) {
                        $package = null;







                        if (isset($_package['packages'][0])) {
                            $package = $_package['packages'][0];
                        }







                        $this->_logShipmentErrorsMessage('Invalid method', $shipment);







                        continue;
                    }







                    $methodId = (int)$method[1];
                }















                $method = $this->unifaunShippingMethodFactory->create()->load($methodId);















                if ($method->isObjectNew()) {
                    $package = null;







                    if (isset($_package['packages'][0])) {
                        $package = $_package['packages'][0];
                    }







                    $this->_logShipmentErrorsMessage('Shipping Method dose not exists.', $shipment);







                    continue;
                }















                $adviceType = null;







                if (array_key_exists('advice', $_package)) {
                    $adviceType = $_package['advice'];
                }







                







                if ($method->getLabelOnly()){
                    continue;
                } elseif ($method->getNoBooking()) {
                    continue;
                }







                $settings = array();















                $orderNumner = isset($params['unifaun_order_number'])? $params['unifaun_order_number'] : '';







                $consigneeReference = isset($params['unifaun_consignee_reference'])? $params['unifaun_consignee_reference'] : '';







                $consignmentNo = 



                (isset($params['unifaun_consignment_number']))? $params['unifaun_consignment_number'] : '';















                $settings['reference'] = array(







                    'order_number'          => $orderNumner,







                    'consignee_reference'   => $consigneeReference,







                    'consignment_number'   => $consignmentNo







                );















                if (isset($params['unifaun_advice_contact'])) {
                    $adviceTypeContact = $params['unifaun_advice_contact'];
                } else {
                    $adviceTypeContact = $this->_getShipmentConfig($shipment, 'unifaun_advice_contact');
                }















                if (isset($params['unifaun_advice_mobile'])) {
                    $adviceTypeMobile = $this->unifaunHelper->sanitizePhoneNumber($params['unifaun_advice_mobile']);
                }else {
                    $adviceTypeMobile = $this->unifaunHelper->sanitizePhoneNumber($this->_getShipmentConfig($shipment, 'unifaun_advice_mobile'));
                }















                if (isset($params['unifaun_advice_email'])) {
                    $adviceTypeEmail = $params['unifaun_advice_email'];
                } else {
                    return;



                    $adviceTypeEmail = $this->_getSettingsForShipment($shipment, 'unifaun_advice_email');
                }















                if (isset($params['unifaun_advice_phone'])) {
                    $adviceTypePhone = $this->unifaunHelper->sanitizePhoneNumber($params['unifaun_advice_phone']);
                } else {
                    $adviceTypePhone = $this->unifaunHelper->sanitizePhoneNumber($this->_getShipmentConfig($shipment, 'unifaun_advice_phone'));
                }















                if (isset($params['unifaun_advice_fax'])) {
                    $adviceTypeFax = $this->unifaunHelper->sanitizePhoneNumber($params['unifaun_advice_fax']);
                } else {
                    $adviceTypeFax = $this->unifaunHelper->sanitizePhoneNumber($this->_getShipmentConfig($shipment, 'unifaun_advice_fax'));
                }















                if (isset($params['unifaun_automatic_booking'])) {
                    $automaticBooking = ($params['unifaun_automatic_booking'] == "Y");
                } else {
                    $automaticBooking = $this->_getShipmentConfig($shipment, 'unifaun_automatic_booking');
                }















                // $settings = array();















                // $orderNumner = $params['unifaun_order_number'];







                // $consigneeReference = $params['unifaun_consignee_reference'];







                // $consignmentNo = $params['unifaun_consignment_number'];















                // $settings['reference'] = array(







                //     'order_number'          => $orderNumner,







                //     'consignee_reference'   => $consigneeReference,







                //     'consignment_number'   => $consignmentNo







                //     );















                $settings['advice'] = array(







                    "type"      => $adviceType,







                    "mobile"    => $adviceTypeMobile,







                    "contact"   => $adviceTypeContact,







                    "email"     => $adviceTypeEmail,







                    "fax"       => $adviceTypeFax,







                    "phone"     => $adviceTypePhone);















                $settings['automaticBooking'] = $automaticBooking;









                if ($automaticBooking && $params['unifaun_pickup'] == "Y") {
                    $settings['pickup'] = array(







                        "date"        => $params['unifaun_pickup_date'],







                        "earliest"    => trim($params['unifaun_pickup_earliest']),







                        "latest"      => trim($params['unifaun_pickup_latest']),







                        "instruction" => null,







                        "location"    => null,







                    );
                }















                if ($params['unifaun_cod'] == "Y") {
                    $settings['cod'] = array(







                        "amount"        => $params['unifaun_cod_amount'],







                        "currency"      => $params['unifaun_cod_currency'],







                        "paymentMethod" => $params['unifaun_cod_paymentmethod'],







                        "accountNo"     => $params['unifaun_cod_accountno'],







                        "reference"     => $params['unifaun_cod_reference'],







                    );
                }



                if ($params['unifaun_pickup_location']) {
                    $settings['pickup_address'] = $params['unifaun_pickup_location'];
                }




                if(isset($params['insurance']) && $params['insurance'] == 'Y'){
                    $settings['insurance'] = array(



                        'amount' => (isset($params['insurance_amount'])) ? $params['insurance_amount'] : 0,



                        'currency' => (isset($params['unifaun_cod_currency']))? $params['unifaun_cod_currency'] : 'SEK'



                    );
                }


                $firstParcel = reset($parcels);

                $shippingMethod = (isset($firstParcel['shippingMethod']))? $firstParcel['shippingMethod'] : 0;

                $shippingMethod = $this->unifaunShippingMethodFactory->create()->load($shippingMethod);



                if ($shippingMethod && $shippingMethod->getData('shipping_service') == \Pcxpress\Unifaun\Helper\Data::WEBTA_SHIPPING_ID) {

                     

                    if ($method->getMultipleParcels()) {
                        if (!$this->setShippingBooking($_package['packages'], $shipment, $method, $settings, $returnedShipment)) {
                            $failedPackages[] = $_package['packages'];


                            continue;
                        }
                    } else {
                        foreach ($_package['packages'] as $package) {
                        $packages = array($package); // _createShippingBooking() want package argument as array.
                            
                            
                            if (!$this->setShippingBooking($packages, $shipment, $method, $settings, $returnedShipment)) {
                                    $failedPackages[] = array();
                                    continue;
                            }
                            
                        }
                    }
                }// end WEBTA_SHIPPING_ID condition


            }
        }


        $firstParcel = reset($parcels);



        $shippingMethod = (isset($firstParcel['shippingMethod']))? $firstParcel['shippingMethod'] : 0;

                $shippingMethod = $this->unifaunShippingMethodFactory->create()->load($shippingMethod);


        $order->setData('template_method_data', $shippingMethod->getData());


        if ($shippingMethod && $shippingMethod->getData('shipping_service') == \Pcxpress\Unifaun\Helper\Data::PACTSOFT_SHIPPING_ID) {

            die('pacc');
            $transactionId = $order->getRealOrderId() . "-" . microtime(true);

            $pacsoftHelper = $this->unifaunPacsoftDataHelper;
            $pacsoftHelper->createShipment(
                $params, 
                $transactionId, 
                $parcels,
                $order
            );

     }
 



    if (count($failedPackages)) {
        return false;
    }







    return true;







}















protected function _logShipmentErrorsMessage($message, \Magento\Sales\Model\Order\Shipment $shipment = null)
{







    if ($shipment instanceof \Magento\Sales\Model\Order\Shipment && $shipment->getIncrementId()) {
        $message = $shipment->getIncrementId() . ": " . $message;
    }







    $this->backendSession->addError($message);







}















protected function _getShipmentConfig(\Magento\Sales\Model\Order\Shipment $shipment, $key = null, $settings = array())
{







    $order                                = $shipment->getOrder();







    $config                               = array();









    $config['apport_automatic_booking']   = $this->unifaunPcxpressUnifaunConsignmentFactory->create()->getAutomaticBooking() ? 'Y' : null;







    $config['apport_advice_contact']      = $order->getShippingAddress()->getName();







    $config['apport_advice_mobile']       = $order->getShippingAddress()->getTelephone();







    $config['apport_advice_email']        = $order->getShippingAddress()->getEmail();







    $config['apport_advice_fax']          = $order->getShippingAddress()->getFax();







    $config['apport_advice_phone']        = $order->getShippingAddress()->getTelephone();







    $config['apport_order_number']        = $order->getRealOrderId();







    $config['apport_consignee_reference'] = "";







        $config['apport_cod']                 = 'N'; // Set to no







        $config['apport_cod_amount']          = null;







        $config['apport_cod_currency']        = null;







        $config['apport_cod_paymentmethod']   = null;







        $config['apport_cod_accountno']       = null;







        $config['apport_cod_reference']       = null;















        if ($key) {
            if (array_key_exists($key, $settings)) {
                return $settings[$key];
            } else {
                return null;
            }
        }







        return $settings;







}















    protected function setShippingBooking(
        $packages,
        \Magento\Sales\Model\Order\Shipment $shipment,
        \Pcxpress\Unifaun\Model\ShippingMethod $shippingMethod,
        $settings = array(),
        $returnedShipment = false
    ) {        


        $order = $shipment->getOrder();







        $this->_scopeId = $order->getStoreId();


        if ($shippingMethod->getTemplateName() == "") {
            $this->_logShipmentErrorsMessage("No template set for the selected shipping method", $shipment);

            return false;
        }



        $unifaun = $this->unifaunPcxpressUnifaunFactory->create();

        $consignment = $this->unifaunPcxpressUnifaunConsignmentFactory->create();

        if (isset($settings['reference'])){
            if (isset($settings['reference']['order_number'])) {
                if ($returnedShipment) {
                    $consignment->setOrderNo('');
                } else {
                    $consignment->setOrderNo($settings['reference']['order_number']);
                }
            }
        } else {
            $consignment->setOrderNo($order->getRealOrderId());
        }




        $consignment->setTemplateName($shippingMethod->getTemplateName());



        if ($returnedShipment) {
            $transportProduct = $this->unifaunPcxpressUnifaunTransportProductFactory->create();
            $transportProduct->unsetPaymentInstruction();
        }else {
            $transportProduct = $this->unifaunShippingHelper->getTransportProduct($settings, $shippingMethod, $order);
        }



        $shipmentItems = $shipment->getAllItems();


        $sendGoodsValue = $this->_getConfigValue('send_goods_value');


        if (count($shipmentItems) > 0 && $sendGoodsValue) {
            $currencyCode = $shipment->getOrder()->getOrderCurrencyCode();







            $customsClearance = $this->unifaunPcxpressUnifaunCustomsClearanceFactory->create();







            $goodsValue = 0;



            foreach ($shipmentItems as $item) {
                $priceWithDiscount = $item->getPrice() - $item->getDiscountAmount();







                $goodsValue += $priceWithDiscount * $item->getQty();
            }



            $goodsValue = number_format((float)$goodsValue, 2, '.', '');



            $customsClearance->setGoodsValue($goodsValue);


            $customsClearance->setGoodsValueCurrency($currencyCode);


            $transportProduct->setCustomsClearance($customsClearance);
        }





        if(!empty($settings['insurance']['amount'])){
            $insurance = $this->unifaunPcxpressUnifaunInsuranceFactory->create();



            $insurance->setAmount($settings['insurance']['amount']);



            $insurance->setCurrency($settings['insurance']['currency']);



            $transportProduct->setInsurance($insurance);
        }

        $consignment->setTransportProduct($transportProduct);


        $returnedShipment = ($this->request->getParam('submit') === self::RETURN_CONSIGNMENT)? true : false;



        $addressConsignor = $this->unifaunConsignorHelper->getConsignorPart($order, $returnedShipment);

        $addressConsignee = $this->unifaunConsigneeHelper->getConsigneeAddress($order, $returnedShipment);

        $address = $order->getShippingAddress();

        $consignment->addPart($addressConsignor);    

        if (isset($settings['pickup_address'])) {
            $partPickupAddress = $this->_getPickupLocationPart($settings['pickup_address']);
            $consignment->addPart($partPickupAddress);
        }

        $communicationConsignee = $this->unifaunPcxpressUnifaunCommunicationFactory->create();

        if (isset($settings['advice'])) {
            if (isset($settings['advice']['mobile'])) {
                $countryCode = $address->getCountry();

                if ($returnedShipment) {                        
                    $consigneeMobileNumber = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/phone', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                }else{
                    $consigneeMobileNumber = $settings['advice']['mobile'];
                }

                $mobileNumber = $this->unifaunHelper->getCountryPhCode($consigneeMobileNumber, $countryCode);
                $communicationConsignee->setMobile($mobileNumber);
            }

            if (isset($settings['advice']['contact'])) {
                if ($returnedShipment) {
                    $consigneeContact = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/contact_person', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                }else{
                    $consigneeContact = $settings['advice']['contact'];
                }


                $communicationConsignee->setContactPerson($consigneeContact);
            }




            if (isset($settings['advice']['email'])) {
                if ($returnedShipment) {
                    $consigneeEmail = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                }else{
                    $consigneeEmail = $settings['advice']['email'];
                }

                $communicationConsignee->setEmail($consigneeEmail);
            }



            if (isset($settings['advice']['phone'])) {
                $countryCode = $address->getCountry();


                if ($returnedShipment) {
                    $consigneePhoneNumber = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/phone', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                }else{
                    $consigneePhoneNumber = $settings['advice']['phone'];
                }

                $phoneNumber = $this->unifaunHelper->getCountryPhCode($consigneePhoneNumber, $countryCode);

                $communicationConsignee->setPhone($phoneNumber);
            }
        }


        $adviceType = $transportProduct->getAdviceType();

        $communicationConsignee->setNotifyBy($adviceType);

        $part = $this->unifaunPcxpressUnifaunPartFactory->create();

        $part->setAddress($addressConsignee);    


        $part->setCommunication($communicationConsignee);

        $part->setRole("consignee");

        $consignment->addPart($part);

        if (isset($settings['reference'])) {
            if (isset($settings['reference']['consignee_reference'])) {
                $referenceConsignee = $this->unifaunPcxpressUnifaunReferenceFactory->create();


                $referenceConsignee->setReference($settings['reference']['consignee_reference']);


                $part->setReference($referenceConsignee);
            }
        }

        foreach ($packages as $package) {
            if(!isset($package['weight'])){
                $noWeight = true;
            }elseif($package['weight'] == ""){
                $noWeight = true;
            }else{
                $noWeight = false;
            }

            if ($noWeight) {
                $this->_logShipmentErrorsMessage('Package weight no available', $shipment, $package);
                continue;
            }


            $goodsItem = $this->unifaunPcxpressUnifaunGoodsItemFactory->create();

            $goodsItem->setNoOfPackages(1);

            $goodsItem->setWeight($this->unifaunHelper->convertWeightFromStoreToUnifaun($package['weight']));


            $goodsItem->setWeightUnit($this->unifaunHelper->getUnifaunWeightUnit());

                // We only set the dimensions if we have them

            if (isset($package['width']) && isset($package['height']) && isset($package['depth'])) 

            {
                if(floatval($package['width']) > 0 && floatval($package['height']) > 0 && floatval($package['depth']) > 0){
                    $goodsItem->setLengthUnit($this->unifaunHelper->getUnifaunLengthUnit());


                    $goodsItem->setWidth($this->unifaunHelper->convertLengthFromStoreToUnifaun($package['width']));


                    $goodsItem->setHeight($this->unifaunHelper->convertLengthFromStoreToUnifaun($package['height']));


                    $goodsItem->setLength($this->unifaunHelper->convertLengthFromStoreToUnifaun($package['depth']));
                }
            }


            if (isset($package['goodsType']) && $package['goodsType']) {
                $goodsItem->setGoodsType($package['goodsType']);
            } else {
                $goodsItem->setGoodsType($this->unifaunHelper->getDefaultGoodsType());
            }

            $consignment->addGoodsItem($goodsItem);
        }


        if (count($consignment->getGoodsItems()) == 0) {
            $this->_logShipmentErrorsMessage(__('No valid packages'), $shipment, $package);

            return false;
        }

        


        if (isset($settings['automaticBooking'])) {
            if($settings["automaticBooking"] == true){
                $consignmentResult = $unifaun->bookConsignment($consignment, $transactionId);
            }



            else{
                $consignmentResult = $unifaun->saveConsignment($consignment, $transactionId);
            }
        } else {
            $consignmentResult = $unifaun->saveConsignment($consignment, $transactionId);
        }



        if (!$consignmentResult) {
            $this->_logShipmentErrorsMessage(__('Invalid response from Pcxpress Unifaun'), $shipment, $package);
        }





        if ($consignmentResult instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Error) {
            $this->_logShipmentErrorsMessage(__('Order #%s: Error reported from Pcxpress line 1787: %s', $order->getIncrementId(), $consignmentResult->getDescription()), $shipment, $package);
        }



// var_dump($consignmentResult->getTrace());

// var_dump(get_class($consignmentResult));
// var_dump($consignmentResult->getDescription());
// var_dump(get_class_methods($consignmentResult));die;

        $errors = $consignmentResult->getErrors();






        if (is_array($errors) && count($errors) > 0) {
            $messages = array();







            foreach ($errors as $error) {
                $messages[] = $error->getDescription();
            }







            $this->_logShipmentErrorsMessage(__("Order #%s: Error reported from Pcxpress line 1829 :\n %s", $order->getIncrementId(), join("\n", $messages)), $shipment, $package);







            return false;
        } else if ($errors instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Error) {
            // Catch errors







            $this->_logShipmentErrorsMessage(__("Order #%s: Error reported from Pcxpress line 1847:\n %s", $order->getIncrementId(), $errors->getDescription()), $shipment, $package);







            return false;
        }















        // Add tracking data to consignments







        $consignments = $consignmentResult->getConsignments();







        if (!is_array($consignments)) {
            $consignments = array($consignments);
        }















        $validated = true;







        foreach ($consignments as $consignment) {
            $data = array();







            $data['carrier_code'] = \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE;







            $data['title']        = $shippingMethod->getTitle();







            $data['method']       = $shippingMethod->getShippingmethodId();




            $new_tracking_number = $consignment->getConsignmentNo();


            $data['number']       = $new_tracking_number;
            $data['track_number']       = $new_tracking_number;

            $data['parent_id'] = $shipment->getId();





            $track = $this->salesOrderShipmentTrackFactory->create()->addData($data); /* @var $track Mage_Sales_Model_Order_Shipment_Track */
            // $track = Mage::getModel('sales/order_shipment_track')->load(153); /* @var $track Mage_Sales_Model_Order_Shipment_Track */

            // var_dump($data);
            // $track->setData('track_number', $consignment->getConsignmentNo());
            // $track->save();die;



            $shipment->addTrack($track);















            // Add Consignment Id as a invisible comment







            $consignmentId = $consignment->getConsignmentId();







            $shipment->addComment('Consignment Id: ' . $consignmentId, false, false);















            // Check validation status for consignment







            $isComplete = $unifaun->getConsignmentStatusIsComplete($consignmentId);




            if ($isComplete instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Error || $isComplete === null) {
                $validated = false;
            }
        }

        if ($validated) {
            $shipment->setShipmentStatus(1); // If Pcxpress validated all consignments, we change the status of the shipment to indicate this in consignment view in Magento.
        }

        $tracks = $shipment->getTracksCollection();
        
        foreach ($tracks as $shipmentTrack) {
            // $track_number = $shipmentTrack->getData('track_number');
            $tracking_id = $shipmentTrack->getId();
            if (trim($tracking_id) != trim($new_tracking_number)) {
                $this->updateTrackingCode($tracking_id, $new_tracking_number);    
            }            
        }

            // $shipment->save();
  //       var_dump(get_class_methods($shipment));
        // var_dump($shipment->getId());die;


        return true;

    }

    protected function updateTrackingCode($tracking_id, $track_number)
    {
        if (!$tracking_id) {
            return;
        }

        // var_dump($tracking_id);
        // var_dump($track_number);

        

        // var_dump($this->shipment_track_table);die;

        $query = "UPDATE {$this->shipment_track_table} SET track_number = '{$track_number}' WHERE entity_id = '{$tracking_id}'";

        // var_dump($query);

        $this->writeConnection->query($query);

    }


    protected function _getPickupLocationPart($addressId)
    {
        $pickupLocation = $this->unifaunPickupLocationFactory->create()->load($addressId);

        if (!$pickupLocation) {
            throw new \Exception('Pickup Location not found');
        }

        $address = $this->unifaunPcxpressUnifaunAddressFactory->create();
        $address->setId("-");

        $address->setName($pickupLocation->getName());

        $address->setAddress($pickupLocation->getAddress());

        $address->setCity($pickupLocation->getCity());

        $address->setPostCode($pickupLocation->getPostcode());

        $address->setState($pickupLocation->getState());

        $address->setCountryCode($pickupLocation->getCountrycode());

        $communication = $this->unifaunPcxpressUnifaunCommunicationFactory->create();

        if ($pickupLocation->getContactPerson()) {
            $communication->setContactPerson($pickupLocation->getContactPerson());
        }

        if ($pickupLocation->getPhone()) {
            $communication->setPhone($pickupLocation->getPhone());
        }

        if ($pickupLocation->getMobile()) {
            $communication->setMobile($pickupLocation->getMobile());
        }

        if ($pickupLocation->getFax()) {
            $communication->setFax($pickupLocation->getFax());
        }

        if ($pickupLocation->getEmail()) {
            $communication->setEmail($pickupLocation->getEmail());
        }

        $part = $this->unifaunPcxpressUnifaunPartFactory->create();

        $part->setAddress($address);
        $part->setCommunication($communication);
        $part->setRole("pickupAddress");
        return $part;
    }

    protected function _getShipmentWeight(\Magento\Sales\Model\Order\Shipment $shipment)
    {
        $totalWeight = 0;
        $totalQty = 0;

        foreach ($shipment->getAllItems() as $item) {
            $weight = (float) $item->getWeight();

            $qty = $item->getOrderItem()->getQtyToShip() * 1; // Will not give the quantity set in invoice configuration
            $totalQty += $qty;

            $totalWeight += $weight * $qty;
        }

        return $totalWeight;
    }


    protected function _getPackagesByMethodAndAdvice($parcels)
    {

        $packagesByMethodAndAdvice = array();

        
        foreach ($parcels as $package) {
            $packageKey = $package['shippingMethod']."-".$package['advice'];

            if (!array_key_exists($packageKey, $packagesByMethodAndAdvice)) {
                $packagesByMethodAndAdvice[$packageKey] = array(

                    'method'    => $package['shippingMethod'],

                    'advice'    => $package['advice'],

                    'packages'  => array()

                );
            }

            $packagesByMethodAndAdvice[$packageKey]['packages'][] = $package;
        }

        return $packagesByMethodAndAdvice;

    }


    public function addMassActionInSalesOrders(\Magento\Framework\Event\Observer $observer)
    {

        $block = $observer->getEvent()->getBlock();







        if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction && $block->getRequest()->getControllerName() == 'sales_order') {
            if ($block->getParentBlock() instanceof \Magento\Backend\Block\Widget\Grid) {
                $block->addItem(
                    'unifaun_create_consignment', array(







                    'label' => __('Unifaun: Create consignments'),







                    'url' => 'javascript:unifaunModule.batchProcessOrders(' . $block->getJsObjectName() . ', "' . $block->getUrl('unifaun/adminhtml_unifaunConsignment/checkbatch') . '", "' . $block->getUrl('unifaun/adminhtml_unifaunConsignment/batch') . '")',







                    )
                );







                $block->addItem(
                    'unifaun_create_consignment_print', array(







                    'label' => __('Unifaun: Create consignments and labels'),







                    'url' => 'javascript:unifaunModule.batchProcessOrders(' . $block->getJsObjectName() . ', "' . $block->getUrl('unifaun/adminhtml_unifaunConsignment/batchCheck') . '", "' . $block->getUrl('unifaun/adminhtml_unifaunConsignment/createAndPrint') . '")',







                    )
                );
            }
        }







    }







    







    public function createConsignmentLabels(\Magento\Framework\Event\Observer $observer)
    {







        $shipment = $observer->getShipment();







        $request = $this->request;







        $order = $shipment->getOrder();



        if (!$order->getId()) {
            throw new \Exception('The dose not exists.');
        }





        $carrierCode = $order->getShippingCarrier()->getCarrierCode();





        if(!$carrierCode){
            $carrierCode = $order->getShippingMethod();
        }





        if (strpos($carrierCode, \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE) !== false) {
            $methodId = null;







            







            if ($this->unifaunHelper->isTemplateChangeEnabled()) {
                $methodId = $request->getParam("unifaun_method_id");
            }















            if (!$methodId) {
                $method = explode("_", $order->getShippingMethod());







                if (!array_key_exists(1, $method)) {
                    throw new \Exception('Not a valid method');
                }







                $methodId = (int)$method[1];
            }















            $method = $this->unifaunShippingMethodFactory->create()->load($methodId);







            if ($method->isObjectNew()) {
                throw new \Exception('Shipping Method no longer exists.');
            }







            if ($method->getLabelOnly()){
                $label = $this->unifaunLabelFactory->create()->load($shipment->getId(), 'shipment_id');







                if ($label->getId()) {
                    return true;
                }







                $labelModel = $this->unifaunLabelFactory->create();







                $labelModel->setStatus(0)







                ->setShipmentId($shipment->getId())







                ->setCreatedAt(time())







                ->save();







                return true;
            }
        }







        return true;







    }







    







    public function setPackageConfiguration(\Magento\Framework\Event\Observer $observer)
    {







        $product = $observer->getProduct();







        $request = $observer->getRequest();















        $productData = $request->getPost('product');







        $packageConfigurations = array();







        if (array_key_exists('package_configuration', $productData) && is_array($productData['package_configuration'])) {
            $packageConfigurationData = $productData['package_configuration'];















            foreach ($packageConfigurationData as $packageValue) {
                if (is_array($packageValue)) {
                    $packageConfig = $this->unifaunPackageConfigFactory->create();







                    $pvWidth = isset($packageValue['width'])? $packageValue['width']:null;







                    $pvHeight = isset($packageValue['height'])? $packageValue['height']:null;







                    $pvDepth = isset($packageValue['depth'])? $packageValue['depth']:null;







                    $pvWeight = isset($packageValue['weight'])? $packageValue['weight']:null;







                    $pvGoodsType = isset($packageValue['goodsType'])? $packageValue['goodsType']:null;







                    $pvShippingMethod = isset($packageValue['shippingMethod'])? $packageValue['shippingMethod']:null;







                    $pvAdvice = isset($packageValue['advice'])? $packageValue['advice']:null;







                    







                    $packageConfig->setWidth(floatval($pvWidth));







                    $packageConfig->setHeight(floatval($pvHeight));







                    $packageConfig->setDepth(floatval($pvDepth));







                    $packageConfig->setWeight(floatval($pvWeight));







                    $packageConfig->setGoodsType($pvGoodsType);







                    $packageConfig->setShippingMethod($pvShippingMethod);







                    $packageConfig->setAdvice($pvAdvice);







                    $packageConfigurations[] = $packageConfig;
                } elseif ($packageValue instanceof \Pcxpress\Unifaun\Model\PackageConfig) {
                    $packageConfigurations[] = $packageValue;
                }
            }
        }







        $product->setPackageConfiguration($packageConfigurations);







        return $this;







    }    







    







    protected function _getConfigValue($field)
    {







        $value = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/' . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->_scopeId);







        return $value;







    }



    public function updateOrderShipment($order)
    {



        

        $parcels = $this->request->getParam('parcel');

        

        if(!count($parcels)){
            return;
        }

        

        $method = explode("_", $order->getShippingMethod());

        

        $methodId = (int)$method[1];





        $method = $this->unifaunShippingMethodFactory->create()->load($methodId);

        

        $unifaunCode = \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE;



        $newMethodId = false;

        $newMethodDesc = '';

        $updated = false;



        if(count($parcels)){
            foreach ($parcels as $parcel) {
                if($parcel['shippingMethod'] &&  $parcel['shippingMethod'] != $method->getId()){
                    $newMethodId = $unifaunCode . '_' . $parcel['shippingMethod'];

                    $newMethod = $this->unifaunShippingMethodFactory->create()->load($parcel['shippingMethod']);                    

                    $newMethodDesc = $newMethod->getData('title');
                }
            }
        }



        if($newMethodId){
            $order->setShippingMethod($newMethodId);            

            $updated = true;
        }

        if($newMethodDesc){
            $order->setShippingDescription($newMethodDesc);

            $updated = true;
        }

        

        if($updated){
            $order->save();
        }

        

        return;



    }







}







