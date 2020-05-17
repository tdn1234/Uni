<?php

namespace Pcxpress\Unifaun\Model\Observer;


class Consignment extends \Pcxpress\Unifaun\Model\Observer\ObserverAbstract
{

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Pcxpress\Unifaun\Model\BoxFactory
     */
    protected $unifaunBoxFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Phone
     */
    protected $unifaunPhoneHelper;

    /**
     * @var \Pcxpress\Unifaun\Helper\Unit
     */
    protected $unifaunUnitHelper;

    /**
     * @var \Magento\Sales\Model\Order\Shipment\TrackFactory
     */
    protected $salesOrderShipmentTrackFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Pcxpress\Unifaun\Model\PickupAddressFactory
     */
    protected $unifaunPickupAddressFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory
     */
    protected $unifaunPcxpressUnifaunFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentFactory
     */
    protected $unifaunPcxpressUnifaunConsignmentFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProductFactory
     */
    protected $unifaunPcxpressUnifaunTransportProductFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CustomsClearanceFactory
     */
    protected $unifaunPcxpressUnifaunCustomsClearanceFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PickupFactory
     */
    protected $unifaunPcxpressUnifaunPickupFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CodFactory
     */
    protected $unifaunPcxpressUnifaunCodFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory
     */
    protected $unifaunPcxpressUnifaunAddressFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory
     */
    protected $unifaunPcxpressUnifaunCommunicationFactory;

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

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Pcxpress\Unifaun\Model\BoxFactory $unifaunBoxFactory,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory,
        \Pcxpress\Unifaun\Helper\Phone $unifaunPhoneHelper,
        \Pcxpress\Unifaun\Helper\Unit $unifaunUnitHelper,
        \Magento\Sales\Model\Order\Shipment\TrackFactory $salesOrderShipmentTrackFactory,
        \Magento\Backend\Model\Session $backendSession,
        \Pcxpress\Unifaun\Model\PickupAddressFactory $unifaunPickupAddressFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory $unifaunPcxpressUnifaunFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentFactory $unifaunPcxpressUnifaunConsignmentFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProductFactory $unifaunPcxpressUnifaunTransportProductFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CustomsClearanceFactory $unifaunPcxpressUnifaunCustomsClearanceFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PickupFactory $unifaunPcxpressUnifaunPickupFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CodFactory $unifaunPcxpressUnifaunCodFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\AddressFactory $unifaunPcxpressUnifaunAddressFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CommunicationFactory $unifaunPcxpressUnifaunCommunicationFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PartFactory $unifaunPcxpressUnifaunPartFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ReferenceFactory $unifaunPcxpressUnifaunReferenceFactory,
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsItemFactory $unifaunPcxpressUnifaunGoodsItemFactory
    )
    {
        $this->unifaunPcxpressUnifaunFactory = $unifaunPcxpressUnifaunFactory;
        $this->unifaunPcxpressUnifaunConsignmentFactory = $unifaunPcxpressUnifaunConsignmentFactory;
        $this->unifaunPcxpressUnifaunTransportProductFactory = $unifaunPcxpressUnifaunTransportProductFactory;
        $this->unifaunPcxpressUnifaunCustomsClearanceFactory = $unifaunPcxpressUnifaunCustomsClearanceFactory;
        $this->unifaunPcxpressUnifaunPickupFactory = $unifaunPcxpressUnifaunPickupFactory;
        $this->unifaunPcxpressUnifaunCodFactory = $unifaunPcxpressUnifaunCodFactory;
        $this->unifaunPcxpressUnifaunAddressFactory = $unifaunPcxpressUnifaunAddressFactory;
        $this->unifaunPcxpressUnifaunCommunicationFactory = $unifaunPcxpressUnifaunCommunicationFactory;
        $this->unifaunPcxpressUnifaunPartFactory = $unifaunPcxpressUnifaunPartFactory;
        $this->unifaunPcxpressUnifaunReferenceFactory = $unifaunPcxpressUnifaunReferenceFactory;
        $this->unifaunPcxpressUnifaunGoodsItemFactory = $unifaunPcxpressUnifaunGoodsItemFactory;
        $this->request = $request;
        $this->unifaunBoxFactory = $unifaunBoxFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
        $this->unifaunPhoneHelper = $unifaunPhoneHelper;
        $this->unifaunUnitHelper = $unifaunUnitHelper;
        $this->salesOrderShipmentTrackFactory = $salesOrderShipmentTrackFactory;
        $this->backendSession = $backendSession;
        $this->unifaunPickupAddressFactory = $unifaunPickupAddressFactory;
    }

    /**
     * Create a consignment whenever a shipment is created if the shipping method is correct
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @param \Magento\Framework\App\Request\Http $request
     * @throws \Exception
     * @return bool
     */
    public function createConsignment(\Magento\Framework\Event\Observer $observer, \Magento\Framework\App\Request\Http $request = null)
    {
        $shipment = $observer->getShipment();
        /* @var $shipment Mage_Sales_Model_Order_Shipment */

        if (!$shipment->isObjectNew() && !$request) {
            // We can't create shipment with Pcxpress if we update shipment.
            return false;
        }

        // If $request was not passed to this method, get it the old fashion way.
        if (!$request) {
            $request = $this->request;

        }

        $parcels = $request->getParam("parcel");

        if ($parcels == null) {
            // Try to load parcels from package configuration
            $parcels = $this->unifaunBoxFactory->create()->setEntity($shipment)->getNumberOfBoxes();

            if (!count($parcels)) {
                // If we don't have an array with packages, we will use default values for order.
                $parcels = array(
                    array(
                        'weight' => $this->_getShipmentWeight($shipment)
                    )
                );
            }
        } elseif (!is_array($parcels)) {
            throw new \Exception("Package must be an array.");
        }

        $packages = $this->_getPackagesByMethodAndAdvice($parcels);

        $order = $shipment->getOrder();
        if (!$order->getId()) {
            throw new \Exception('The order no longer exists.');
        }

        $carrierCode = $order->getShippingCarrier()->getCarrierCode();
        $failedPackages = array();

        if ($carrierCode == \Pcxpress\Unifaun\Model\Carrier\ShippingMethod::CARRIER_CODE) {
            foreach ($packages as $key => $packageCollection) {
                $methodId = null;

                if ($this->unifaunHelper->isEnabledToSwitchMethod() && array_key_exists('method', $packageCollection)) {
                    $methodId = $packageCollection['method'];
                }

                if (!$methodId) {
                    $method = explode("_", $order->getShippingMethod());
                    if (!array_key_exists(1, $method)) {
                        $package = null;
                        if (isset($packageCollection['packages'][0])) {
                            $package = $packageCollection['packages'][0];
                        }
                        $this->_logErrorMessages('Invalid method', $shipment, $package);
                        continue;
                    }
                    $methodId = (int)$method[1];
                }

                $method = $this->unifaunShippingMethodFactory->create()->load($methodId);
                /* @var $method Pcxpress_Unifaun_Model_ShippingMethod */
                if ($method->isObjectNew()) {
                    $package = null;
                    if (isset($packageCollection['packages'][0])) {
                        $package = $packageCollection['packages'][0];
                    }
                    $this->_logErrorMessages('Shipping Method no longer exists.', $shipment, $package);
                    continue;
                }

                if ($method->getOnlyLabel()) {
                    // The current method is used for labels only
                    // We need to save down some info to the unifaun_labels table
                    // This will be done after we have saved the shipment.
                    continue;
                } elseif ($method->getNoBooking()) {
                    // This method should not be booked
                    continue;
                }

                $adviceType = null;
                if (array_key_exists('advice', $packageCollection)) {
                    $adviceType = $packageCollection['advice'];
                }

                /** @var \Pcxpress\Unifaun\Helper\Phone $phoneHelper */
                $phoneHelper = $this->unifaunPhoneHelper;

                // Additional settings
                if (array_key_exists('unifaun_advice_contact', $request->getParams())) {
                    $adviceTypeContact = $request->getParam("unifaun_advice_contact");
                } else {
                    $adviceTypeContact = $this->_getSettingsForShipment($shipment, 'unifaun_advice_contact');
                }

                if (array_key_exists('unifaun_advice_mobile', $request->getParams())) {
                    $adviceTypeMobile = $phoneHelper->filterPhoneNumber($request->getParam("unifaun_advice_mobile"));
                } else {
                    $adviceTypeMobile = $phoneHelper->filterPhoneNumber($this->_getSettingsForShipment($shipment, 'unifaun_advice_mobile'));
                }

                if (array_key_exists('unifaun_advice_email', $request->getParams())) {
                    $adviceTypeEmail = $request->getParam("unifaun_advice_email");
                } else {
                    $adviceTypeEmail = $this->_getSettingsForShipment($shipment, 'unifaun_advice_email');
                }

                if (array_key_exists('unifaun_advice_phone', $request->getParams())) {
                    $adviceTypePhone = $phoneHelper->filterPhoneNumber($request->getParam("unifaun_advice_phone"));
                } else {
                    $adviceTypePhone = $phoneHelper->filterPhoneNumber($this->_getSettingsForShipment($shipment, 'unifaun_advice_phone'));
                }

                if (array_key_exists('unifaun_advice_mobile', $request->getParams())) {
                    $adviceTypeFax = $phoneHelper->filterPhoneNumber($request->getParam("unifaun_advice_fax"));
                } else {
                    $adviceTypeFax = $phoneHelper->filterPhoneNumber($this->_getSettingsForShipment($shipment, 'unifaun_advice_fax'));
                }

                if (array_key_exists('unifaun_automatic_booking', $request->getParams())) {
                    $automaticBooking = ($request->getParam("unifaun_automatic_booking") == "Y");
                } else {
                    $automaticBooking = $this->_getSettingsForShipment($shipment, 'unifaun_automatic_booking');
                }

                $settings = array();

                // Get order references
                $orderNumner = $request->getParam("unifaun_order_number");
                $consigneeReference = $request->getParam("unifaun_consignee_reference");
                $settings['reference'] = array(
                    'order_number' => $orderNumner,
                    'consignee_reference' => $consigneeReference
                );

                // Advice
                $settings['advice'] = array(
                    "type" => $adviceType,
                    "mobile" => $adviceTypeMobile,
                    "contact" => $adviceTypeContact,
                    "email" => $adviceTypeEmail,
                    "fax" => $adviceTypeFax,
                    "phone" => $adviceTypePhone);
                $settings['automaticBooking'] = $automaticBooking;


                // Pickup
                if ($automaticBooking && $request->getParam("unifaun_pickup") == "Y") {
                    $settings['pickup'] = array(
                        "date" => $request->getParam("unifaun_pickup_date"),
                        "earliest" => trim($request->getParam("unifaun_pickup_earliest")),
                        "latest" => trim($request->getParam("unifaun_pickup_latest")),
                        "instruction" => null,
                        "location" => null,
                    );
                }

                // Cache on delivery
                if ($request->getParam("unifaun_cod") == "Y") {
                    $settings['cod'] = array(
                        "amount" => $request->getParam("unifaun_cod_amount"),
                        "currency" => $request->getParam("unifaun_cod_currency"),
                        "paymentMethod" => $request->getParam("unifaun_cod_paymentmethod"),
                        "accountNo" => $request->getParam("unifaun_cod_accountno"),
                        "reference" => $request->getParam("unifaun_cod_reference"),
                    );
                }

                // Custom pickup location
                if ($request->getParam("unifaun_pickup_location")) {
                    $settings['pickup_address'] = $request->getParam("unifaun_pickup_location");
                }

                // This shipment should be saved using a shipping with Pcxpress Unifaun
                if ($method->getMultipleParcels()) {
                    if (!$this->_createShippingBooking($packageCollection['packages'], $shipment, $method, $settings)) {
                        $failedPackages[] = $packageCollection['packages'];
                        continue;
                    }
                } else {
                    foreach ($packageCollection['packages'] as $package) {
                        $packageArray = array($package); // _createShippingBooking() want package argument as array.
                        if (!$this->_createShippingBooking($packageArray, $shipment, $method, $settings)) {
                            $failedPackages[] = $packageArray;
                            continue;
                        }
                    }
                }
            }
        }

        if (count($failedPackages)) {
            return false;
        }
        return true;
    }

    /**
     * Create a shipping booking at Pcxpress Unifaun
     *
     * @param array $packages
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @param \Pcxpress\Unifaun\Model\ShippingMethod $shippingMethod
     * @param array $settings
     * @throws \Exception
     * @return bool
     */
    protected function _createShippingBooking(
        array $packages,
        \Magento\Sales\Model\Order\Shipment $shipment,
        \Pcxpress\Unifaun\Model\ShippingMethod $shippingMethod,
        array $settings = array()
    )
    {
        $order = $shipment->getOrder();
        $this->_scopeId = $order->getStoreId();

        if ($shippingMethod->getTemplateName() == "") {
            $this->_logErrorMessages("The selected shipping method doesn't have a template", $shipment);
            return false;
        }

        $unifaun = $this->unifaunPcxpressUnifaunFactory->create();

        // Consignment
        $consignment = $this->unifaunPcxpressUnifaunConsignmentFactory->create();

        if (array_key_exists('reference', $settings)) {
            if (isset($settings['reference']['order_number'])) {
                $consignment->setOrderNo($settings['reference']['order_number']);
            }
        } else {
            $consignment->setOrderNo($order->getRealOrderId());
        }
        $consignment->setTemplateName($shippingMethod->getTemplateName());

        // TransportProduct
        $transportProduct = $this->unifaunPcxpressUnifaunTransportProductFactory->create();
        if (array_key_exists("paymentInstruction", $settings) && in_array($settings["paymentInstruction"], array("P", "C", "T"))) {
            $transportProduct->setPaymentInstruction($settings["paymentInstruction"]);
        } else {
            $transportProduct->setPaymentInstruction(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\TransportProduct::PAYMENTINSTRUCTION_PREPAID);
        }

        $shipmentItems = $shipment->getAllItems();
        $sendGoodsValue = $this->_getConfigValue('send_goods_value');
        if (count($shipmentItems) > 0 && $sendGoodsValue) {
            $currencyCode = $shipment->getOrder()->getOrderCurrencyCode();
            $customsClearance = $this->unifaunPcxpressUnifaunCustomsClearanceFactory->create();
            $goodsValue = 0;

            foreach ($shipmentItems as $item) {
                /** @var \Mage_Sales_Model_Order_Item $item */
                $priceWithDiscount = $item->getPrice() - $item->getDiscountAmount();
                $goodsValue += $priceWithDiscount * $item->getQty();
            }

            $goodsValue = number_format((float)$goodsValue, 2, '.', '');

            $customsClearance->setGoodsValue($goodsValue);
            $customsClearance->setGoodsValueCurrency($currencyCode);
            $transportProduct->setCustomsClearance($customsClearance);
        }

        // Advice
        $advice = false;
        $adviceType = null;
        if (array_key_exists("advice", $settings) && array_key_exists("type", $settings['advice'])) {
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


        $shippingTemplate = $shippingMethod->getTemplateName();
        $mypackTemplateName = "mypack";
        if (stripos($shippingTemplate, $mypackTemplateName) !== false) {
            $transportProduct->setAdvice($advice);
        }


        $schenkerTemplateName = "schenker";
        if (stripos($shippingTemplate, $schenkerTemplateName) !== false && $adviceType == \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::MOBILE) {
            $transportProduct->setAdvice($advice);
        }

        // Pickup
        if (array_key_exists("pickup", $settings)) {
            $pickup = $this->unifaunPcxpressUnifaunPickupFactory->create();
            $pickup->setDate($settings['pickup']['date']);
            $pickup->setEarliest($settings['pickup']['earliest']);
            $pickup->setLatest($settings['pickup']['latest']);
            $pickup->setInstruction($settings['pickup']['instruction']);
            $pickup->setLocation($settings['pickup']['location']);
            $transportProduct->setPickup($pickup);
        }

        // Cash on delivery
        if (array_key_exists("cod", $settings)) {
            $cod = $this->unifaunPcxpressUnifaunCodFactory->create();
            $cod->setAmount($settings['cod']['amount']);
            $cod->setPaymentMethod($settings['cod']['paymentMethod']);
            $cod->setCurrency($settings['cod']['currency']);
            $cod->setAccountNo($settings['cod']['accountNo']);
            $cod->setReference($settings['cod']['reference']);
            $transportProduct->setCod($cod);
        }

        $consignment->setTransportProduct($transportProduct);

        // Consignor Address
        $partConsignor = $this->_getConsignorPart();
        $consignment->addPart($partConsignor);

        if (isset($settings['pickup_address'])) {
            $partPickupAddress = $this->_getPickupAddressPart($settings['pickup_address']);
            $consignment->addPart($partPickupAddress);
        }


        $address = $order->getShippingAddress();
        /* @var $address Mage_Sales_Model_Order_Address */

        // Consignee Address
        $consigneeName = $address->getName();
        if ($address->getCompany()) {
            $consigneeName = $address->getCompany();
        }
        $addressConsignee = $this->unifaunPcxpressUnifaunAddressFactory->create();
        $addressConsignee->setId("-");
        $addressConsignee->setName($consigneeName);
        $addressConsignee->setAddress($address->getStreet1(), $address->getStreet2(), $address->getStreet3());
        $addressConsignee->setCity($address->getCity());
        $addressConsignee->setPostCode($address->getPostcode());
        $addressConsignee->setState($address->getRegionCode()); // getRegionCode() - Return 2 letter state code if available, otherwise full region name
        $addressConsignee->setCountryCode($address->getCountry());

        /** @var \Pcxpress\Unifaun\Helper\Phone $phoneHelper */
        $phoneHelper = $this->unifaunPhoneHelper;

        // Communication
        $communicationConsignee = $this->unifaunPcxpressUnifaunCommunicationFactory->create();
        if (array_key_exists("advice", $settings)) {
            if (array_key_exists("mobile", $settings['advice'])) {
                $countryCode = $address->getCountry();
                $mobileNumber = $phoneHelper->getCountryPhonePrefix($settings['advice']['mobile'], $countryCode);
                $communicationConsignee->setMobile($mobileNumber);
            }
            if (array_key_exists("contact", $settings['advice'])) {
                $communicationConsignee->setContactPerson($settings['advice']['contact']);
            }
            if (array_key_exists("email", $settings['advice'])) {
                $communicationConsignee->setEmail($settings['advice']['email']);
            }
            if (array_key_exists("phone", $settings['advice'])) {
                $countryCode = $address->getCountry();
                $phoneNumber = $phoneHelper->getCountryPhonePrefix($settings['advice']['phone'], $countryCode);
                $communicationConsignee->setPhone($phoneNumber);
            }
            if (array_key_exists("fax", $settings['advice'])) {
                //$communicationConsignee->setFax($settings['advice']['fax']);
            }
        }
        $communicationConsignee->setNotifyBy($adviceType);

        // Consignee Part
        $partConsignee = $this->unifaunPcxpressUnifaunPartFactory->create();
        $partConsignee->setAddress($addressConsignee);
        $partConsignee->setCommunication($communicationConsignee);
        $partConsignee->setRole("consignee");
        $consignment->addPart($partConsignee);


        if (array_key_exists('reference', $settings)) {
            if (isset($settings['reference']['consignee_reference'])) {
                $referenceConsignee = $this->unifaunPcxpressUnifaunReferenceFactory->create();
                $referenceConsignee->setReference($settings['reference']['consignee_reference']);
                $partConsignee->setReference($referenceConsignee);
            }
        }


        // Goods Items
        /** @var  \Pcxpress\Unifaun\Helper\Unit $unitHelper */
        $unitHelper = $this->unifaunUnitHelper;
        foreach ($packages as $package) {
            $weightDontExists = (!array_key_exists("weight", $package) || $package['weight'] == "");
            if ($weightDontExists) {
                $this->_logErrorMessages('Package is missing weight', $shipment, $package);
                continue;
            }

            $goodsItem = $this->unifaunPcxpressUnifaunGoodsItemFactory->create();
            $goodsItem->setNoOfPackages(1);

            $goodsItem->setWeight($unitHelper->convertWeightFromStoreToUnifaun($package['weight']));
            $goodsItem->setWeightUnit($unitHelper->getUnifaunWeightUnit());

            // We only set the dimensions if we have them
            if (array_key_exists("width", $package) &&
                array_key_exists("height", $package) &&
                array_key_exists("depth", $package) &&
                floatval($package['width']) > 0 &&
                floatval($package['height']) > 0 &&
                floatval($package['depth']) > 0
            ) {

                $goodsItem->setLengthUnit($unitHelper->getUnifaunLengthUnit());
                $goodsItem->setWidth($unitHelper->convertLengthFromStoreToUnifaun($package['width']));
                $goodsItem->setHeight($unitHelper->convertLengthFromStoreToUnifaun($package['height']));
                $goodsItem->setLength($unitHelper->convertLengthFromStoreToUnifaun($package['depth']));
            }

            if (isset($package['goodsType']) && $package['goodsType']) {
                $goodsItem->setGoodsType($package['goodsType']);
            } else {
                $goodsItem->setGoodsType($this->unifaunHelper->getDefaultGoodsType());
            }

//            $goodsItem->setPackageType('FBX');

            $consignment->addGoodsItem($goodsItem);
        }

        if (count($consignment->getGoodsItems()) == 0) {
            $this->_logErrorMessages(__('No valid packages'), $shipment, $package);
            return false;
        }

        $transactionId = $order->getRealOrderId() . "-" . microtime(true);

        if (array_key_exists("automaticBooking", $settings) && $settings["automaticBooking"] == true) {
            $consignmentResult = $unifaun->bookConsignment($consignment, $transactionId);
        } else {
            $consignmentResult = $unifaun->saveConsignment($consignment, $transactionId);
        }

        // Check if this was a debug call
        // if (Mage::helper("unifaun/data")->isDebug()) {
        //     return true;
        // }

        // Error handling for exceptions
        if (!$consignmentResult) {
            $this->_logErrorMessages(__('Invalid response from Pcxpress'), $shipment, $package);
        }

        if ($consignmentResult instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Error) {
            $this->_logErrorMessages(__('Order #%s: Error reported from Pcxpress line 484: %s', $order->getIncrementId(), $consignmentResult->getDescription()), $shipment, $package);
        }

        $errors = $consignmentResult->getErrors();
        if (is_array($errors) && count($errors) > 0) {
            $messages = array();
            foreach ($errors as $error) {
                $messages[] = $error->getDescription();
            }
            $this->_logErrorMessages(__("Order #%s: Error reported from Pcxpress line 493:\n %s", $order->getIncrementId(), join("\n", $messages)), $shipment, $package);
            return false;
        } else if ($errors instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Error) {
            // Catch errors
            $this->_logErrorMessages(__("Order #%s: Error reported from Pcxpress line 497:\n %s", $order->getIncrementId(), $errors->getDescription()), $shipment, $package);
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
            $data['carrier_code'] = \Pcxpress\Unifaun\Model\Carrier\ShippingMethod::CARRIER_CODE;
            $data['title'] = $shippingMethod->getTitle();
            $data['method'] = $shippingMethod->getShippingmethodId();
            $data['number'] = $consignment->getConsignmentNo();
            $track = $this->salesOrderShipmentTrackFactory->create()->addData($data);
            /* @var $track Mage_Sales_Model_Order_Shipment_Track */
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

        return true;
    }

    /**
     * @param $message
     * @param \Magento\Sales\Model\Order\Shipment|null $shipment
     * @param array|null $package
     */
    protected function _logErrorMessages($message, \Magento\Sales\Model\Order\Shipment $shipment = null, $package = null)
    {
        if ($shipment instanceof \Magento\Sales\Model\Order\Shipment && $shipment->getIncrementId()) {
            $message = $shipment->getIncrementId() . ": " . $message;
        }
        $this->backendSession->addError($message);
    }

    /**
     * Get weight of all items in current shipment
     *
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @return int
     */
    protected function _getShipmentWeight(\Magento\Sales\Model\Order\Shipment $shipment)
    {
        $totalWeight = 0;
        $totalQty = 0;

        foreach ($shipment->getAllItems() as $item) {
            $weight = (float)$item->getWeight();
            $qty = $item->getOrderItem()->getQtyToShip() * 1; // Will not give the quantity set in invoice configuration
            $totalQty += $qty;
            $totalWeight += $weight * $qty;
        }

        return $totalWeight;
    }

    /**
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @param null $key
     * @return array|null
     */
    protected function _getSettingsForShipment(\Magento\Sales\Model\Order\Shipment $shipment, $key = null)
    {
        $order = $shipment->getOrder();
        $settings = array();
        $settings['unifaun_automatic_booking'] = $this->unifaunHelper->getAutomaticBooking() ? 'Y' : null;
        $settings['unifaun_advice_contact'] = $order->getShippingAddress()->getName();
        $settings['unifaun_advice_mobile'] = $order->getShippingAddress()->getTelephone();
        $settings['unifaun_advice_email'] = $order->getShippingAddress()->getEmail();
        $settings['unifaun_advice_fax'] = $order->getShippingAddress()->getFax();
        $settings['unifaun_advice_phone'] = $order->getShippingAddress()->getTelephone();
        $settings['unifaun_order_number'] = $order->getRealOrderId();
        $settings['unifaun_consignee_reference'] = "";
        $settings['unifaun_cod'] = 'N'; // Set to no
        $settings['unifaun_cod_amount'] = null;
        $settings['unifaun_cod_currency'] = null;
        $settings['unifaun_cod_paymentmethod'] = null;
        $settings['unifaun_cod_accountno'] = null;
        $settings['unifaun_cod_reference'] = null;

        if ($key) {
            if (array_key_exists($key, $settings)) {
                return $settings[$key];
            } else {
                return null;
            }
        }
        return $settings;
    }

    /**
     * @param $parcels
     * @return array
     */
    protected function _getPackagesByMethodAndAdvice($parcels)
    {
        $packagesByMethodAndAdvice = array();
        foreach ($parcels as $package) {
            $packageKey = $package['shippingMethod'] . "-" . $package['advice'];

            if (!array_key_exists($packageKey, $packagesByMethodAndAdvice)) {
                $packagesByMethodAndAdvice[$packageKey] = array(
                    'method' => $package['shippingMethod'],
                    'advice' => $package['advice'],
                    'packages' => array()
                );
            }

            $packagesByMethodAndAdvice[$packageKey]['packages'][] = $package;
        }
        return $packagesByMethodAndAdvice;
    }

    /**
     * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Part
     */
    protected function _getConsignorPart()
    {
        $address = $this->unifaunPcxpressUnifaunAddressFactory->create();
        $address->setId("-");
        $address->setName($this->_getConfigValue("address_name"));
        $address->setAddress(
            $this->_getConfigValue("address_line1"),
            $this->_getConfigValue("address_line2"),
            $this->_getConfigValue("address_line3")
        );
        $address->setCity($this->_getConfigValue("address_city"));
        $address->setPostCode($this->_getConfigValue("address_postcode"));
        $address->setState($this->_getConfigValue("address_state"));
        $address->setCountryCode($this->_getConfigValue("address_countrycode"));

        $communication = $this->unifaunPcxpressUnifaunCommunicationFactory->create();
        $communication->setContactPerson($this->_getConfigValue("contact_person"));
        $communication->setPhone($this->_getConfigValue("contact_phone"));
        $communication->setEmail($this->_getConfigValue("contact_email"));

        $part = $this->unifaunPcxpressUnifaunPartFactory->create();
        $part->setAddress($address);
        $part->setCommunication($communication);
        $part->setRole("consignor");

        return $part;
    }

    /**
     * @param $addressId
     * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Part
     * @throws \Exception
     */
    private function _getPickupAddressPart($addressId)
    {
        $pickupAddress = $this->unifaunPickupAddressFactory->create()->load($addressId);
        if (!$pickupAddress) {
            throw new \Exception('Could not find pickupAddress');
        }

        $address = $this->unifaunPcxpressUnifaunAddressFactory->create();
        $address->setId("-");
        $address->setName($pickupAddress->getData("address_name"));
        $address->setAddress(
            $pickupAddress->getData("address_address1"),
            $pickupAddress->getData("address_address2"),
            $pickupAddress->getData("address_address3")
        );
        $address->setCity($pickupAddress->getData("address_city"));
        $address->setPostCode($pickupAddress->getData("address_postcode"));
        $address->setState($pickupAddress->getData("address_state"));
        $address->setCountryCode($pickupAddress->getData("address_countrycode"));

        $communication = $this->unifaunPcxpressUnifaunCommunicationFactory->create();
        if ($pickupAddress->getData("communication_contact_person")) {
            $communication->setContactPerson($pickupAddress->getData("communication_contact_person"));
        }
        if ($pickupAddress->getData("communication_phone")) {
            $communication->setPhone($pickupAddress->getData("communication_phone"));
        }
        if ($pickupAddress->getData("communication_mobile")) {
            $communication->setMobile($pickupAddress->getData("communication_mobile"));
        }
        if ($pickupAddress->getData("communication_fax")) {
            $communication->setFax($pickupAddress->getData("communication_fax"));
        }
        if ($pickupAddress->getData("communication_email")) {
            $communication->setEmail($pickupAddress->getData("communication_email"));
        }

        $part = $this->unifaunPcxpressUnifaunPartFactory->create();
        $part->setAddress($address);
        $part->setCommunication($communication);
        $part->setRole("pickupAddress");

        return $part;
    }
}