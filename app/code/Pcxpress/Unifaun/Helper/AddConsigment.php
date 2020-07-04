<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Helper;

use Magento\Framework\App\RequestInterface;
use Pcxpress\Unifaun\Model\Pcxpress\Unifaun;
use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Transport;
use Pcxpress\Unifaun\Helper\Shipping;
use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\CustomsClearance;
use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Insurance;
use Pcxpress\Unifaun\Model\Observer\AddConsignment;
use Pcxpress\Unifaun\Helper\Consignor;
use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication;
use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Part;
use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Reference;
use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\GoodsItem;
use Magento\Sales\Model\Order\Shipment\TrackFactory;
use Pcxpress\Unifaun\Helper\Consignee;

class AddConsigment extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Eav\Model\EntityFactory
     */
    protected $eavEntityFactory;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory
     */
    protected $eavResourceModelEntityAttributeCollectionFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory
     */
    protected $unifaunMysql4ShippingMethodCollectionFactory;

    /** @var Data $unifaunHelper */
    protected $unifaunHelper;

    /** @var Unifaun $unifaunModel */
    protected $unifaunModel;

    /** @var Unifaun\Consignment $consignmentModel */
    protected $consignmentModel;

    /** @var Transport $transportModel */
    protected $transportModel;

    /**
     * @var Communication $communicationModel
     */
    protected $communicationModel;

    /** @var Shipping $unifaunShippingHelper */
    protected $unifaunShippingHelper;

    /**
     * @var Consignor $consignorHelper
     */
    protected $consignorHelper;

    /**
     * @var Consignee $consigneeHelper
     */
    protected $consigneeHelper;

    /**
     * @var CustomsClearance $customsClearanceModel
     */
    protected $customsClearanceModel;

    /**
     * @var Insurance $insuranceModel
     */
    protected $insuranceModel;

    /**
     * @var Part $partModel
     */
    protected $partModel;

    /**
     * @var Reference $referenceModel
     */
    protected $referenceModel;

    /**
     * @var GoodsItem $goodsItemModel
     */
    protected $goodsItemModel;

    /**
     * @var TrackFactory $salesOrderShipmentTrackFactory
     */
    protected $salesOrderShipmentTrackFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $eavResourceModelEntityAttributeCollectionFactory,
        \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory,
        Data $unifaunHelper,
        Unifaun $unifaunModel,
        Unifaun\Consignment $consignmentModel,
        Transport $transportModel,
        Shipping $unifaunShippingHelper,
        CustomsClearance $customsClearanceModel,
        Insurance $insuranceModel,
        RequestInterface $request,
        Consignor $consignorHelper,
        Communication $communicationModel,
        Part $partModel,
        Reference $referenceModel,
        GoodsItem $goodsItemModel,
        TrackFactory $salesOrderShipmentTrackFactory,
        Consignee $consigneeHelper
    )
    {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->eavEntityFactory = $eavEntityFactory;
        $this->eavResourceModelEntityAttributeCollectionFactory = $eavResourceModelEntityAttributeCollectionFactory;
        $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
        $this->unifaunModel = $unifaunModel;
        $this->consignmentModel = $consignmentModel;
        $this->transportModel = $transportModel;
        $this->customsClearanceModel = $customsClearanceModel;
        $this->insuranceModel = $insuranceModel;
        $this->communicationModel = $communicationModel;
        $this->partModel = $partModel;
        $this->referenceModel = $referenceModel;
        $this->goodsItemModel = $goodsItemModel;

        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunShippingHelper = $unifaunShippingHelper;
        $this->consignorHelper = $consignorHelper;
        $this->consigneeHelper = $consigneeHelper;

        $this->salesOrderShipmentTrackFactory = $salesOrderShipmentTrackFactory;

        $this->request = $request;
        parent::__construct(
            $context
        );

        $defaultStoreId = $this->storeManager
            ->getWebsite(true)
            ->getDefaultGroup()
            ->getDefaultStoreId();
    }

    public function _getShipmentConfig(\Magento\Sales\Model\Order\Shipment $shipment, $key = null, $settings = array())
    {

        $order = $shipment->getOrder();
        $config = array();
        //app/code/Pcxpress/Unifaun/Model/Pcxpress/Unifaun/Consignment.php:212
        //$config['apport_automatic_booking'] = $this->unifaunPcxpressUnifaunConsignmentFactory->create()->getAutomaticBooking() ? 'Y' : null;

        $config['apport_automatic_booking'] = $this->unifaunHelper->getAutomaticBooking() ? 'Y' : null;
        $config['apport_advice_contact'] = $order->getShippingAddress()->getName();
        $config['apport_advice_mobile'] = $order->getShippingAddress()->getTelephone();
        $config['apport_advice_email'] = $order->getShippingAddress()->getEmail();
        $config['apport_advice_fax'] = $order->getShippingAddress()->getFax();
        $config['apport_advice_phone'] = $order->getShippingAddress()->getTelephone();
        $config['apport_order_number'] = $order->getRealOrderId();
        $config['apport_consignee_reference'] = "";
        $config['apport_cod'] = 'N'; // Set to no
        $config['apport_cod_amount'] = null;
        $config['apport_cod_currency'] = null;
        $config['apport_cod_paymentmethod'] = null;
        $config['apport_cod_accountno'] = null;
        $config['apport_cod_reference'] = null;
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
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @param null $key
     * @return array|null
     */
    public function _getSettingsForShipment(\Magento\Sales\Model\Order\Shipment $shipment, $key = null)
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

    public function setShippingBooking(
        $packages,
        \Magento\Sales\Model\Order\Shipment $shipment,
        \Pcxpress\Unifaun\Model\ShippingMethod $shippingMethod,
        $settings = array(),
        $returnedShipment = false
    )
    {

        $order = $shipment->getOrder();
        $transactionId = $order->getRealOrderId() . "-" . microtime(true);
        $this->_scopeId = $order->getStoreId();
        if ($shippingMethod->getTemplateName() == "") {
            $this->_logShipmentErrorsMessage("No template set for the selected shipping method", $shipment);

            return false;
        }

//        $unifaun = Mage::getModel('unifaun/pcxpress_unifaun');
//
//        $consignment = Mage::getModel('unifaun/pcxpress_unifaun_consignment');

        $unifaun = $this->unifaunModel;

        $consignment = $this->consignmentModel;

        if (isset($settings['reference'])) {
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
//            $transportProduct = $this->unifaunPcxpressUnifaunTransportProductFactory->create();
            $transportProduct = $this->transportModel;
            $transportProduct->unsetPaymentInstruction();
        } else {
            $transportProduct = $this->unifaunShippingHelper->getTransportProduct($settings, $shippingMethod, $order);
        }


        $shipmentItems = $shipment->getAllItems();


        $sendGoodsValue = $this->unifaunHelper->isSendGoodsValue();


        if (count($shipmentItems) > 0 && $sendGoodsValue) {
            $currencyCode = $shipment->getOrder()->getOrderCurrencyCode();


//            $customsClearance = $this->unifaunPcxpressUnifaunCustomsClearanceFactory->create();
            $customsClearance = $this->customsClearanceModel;


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


        if (!empty($settings['insurance']['amount'])) {
            $insurance = $this->insuranceModel->create();


            $insurance->setAmount($settings['insurance']['amount']);


            $insurance->setCurrency($settings['insurance']['currency']);


            $transportProduct->setInsurance($insurance);
        }

        $consignment->setTransportProduct($transportProduct);


        $returnedShipment = ($this->request->getParam('submit') === AddConsignment::RETURN_CONSIGNMENT) ? true : false;


        $addressConsignor = $this->consignorHelper->getConsignorPart($order, $returnedShipment);

        $addressConsignee = $this->consigneeHelper->getConsigneeAddress($order, $returnedShipment);

        $address = $order->getShippingAddress();

        $consignment->addPart($addressConsignor);

        if (isset($settings['pickup_address'])) {
            $partPickupAddress = $this->_getPickupLocationPart($settings['pickup_address']);
            $consignment->addPart($partPickupAddress);
        }

        $communicationConsignee = $this->communicationModel;

        if (isset($settings['advice'])) {
            if (isset($settings['advice']['mobile'])) {
                $countryCode = $address->getCountry();

                if ($returnedShipment) {
                    $consigneeMobileNumber = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/phone', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                } else {
                    $consigneeMobileNumber = $settings['advice']['mobile'];
                }

                $mobileNumber = $this->unifaunHelper->getCountryPhCode($consigneeMobileNumber, $countryCode);
                $communicationConsignee->setMobile($mobileNumber);
            }

            if (isset($settings['advice']['contact'])) {
                if ($returnedShipment) {
                    $consigneeContact = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/contact_person', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                } else {
                    $consigneeContact = $settings['advice']['contact'];
                }


                $communicationConsignee->setContactPerson($consigneeContact);
            }


            if (isset($settings['advice']['email'])) {
                if ($returnedShipment) {
                    $consigneeEmail = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                } else {
                    $consigneeEmail = $settings['advice']['email'];
                }

                $communicationConsignee->setEmail($consigneeEmail);
            }


            if (isset($settings['advice']['phone'])) {
                $countryCode = $address->getCountry();


                if ($returnedShipment) {
                    $consigneePhoneNumber = $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/phone', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                } else {
                    $consigneePhoneNumber = $settings['advice']['phone'];
                }

                $phoneNumber = $this->unifaunHelper->getCountryPhCode($consigneePhoneNumber, $countryCode);

                $communicationConsignee->setPhone($phoneNumber);
            }
        }


        $adviceType = $transportProduct->getAdviceType();

        $communicationConsignee->setNotifyBy($adviceType);

        $part = $this->partModel;

        $part->setAddress($addressConsignee);


        $part->setCommunication($communicationConsignee);

        $part->setRole("consignee");

        $consignment->addPart($part);

        if (isset($settings['reference'])) {
            if (isset($settings['reference']['consignee_reference'])) {
                $referenceConsignee = $this->referenceModel;


                $referenceConsignee->setReference($settings['reference']['consignee_reference']);


                $part->setReference($referenceConsignee);
            }
        }

        foreach ($packages as $package) {
            if (!isset($package['weight'])) {
                $noWeight = true;
            } elseif ($package['weight'] == "") {
                $noWeight = true;
            } else {
                $noWeight = false;
            }

            if ($noWeight) {
                $this->_logShipmentErrorsMessage('Package weight no available', $shipment, $package);
                continue;
            }


            $goodsItem = $this->goodsItemModel;

            $goodsItem->setNoOfPackages(1);

            $goodsItem->setWeight($this->unifaunHelper->convertWeightFromStoreToUnifaun($package['weight']));


            $goodsItem->setWeightUnit($this->unifaunHelper->getUnifaunWeightUnit());

            // We only set the dimensions if we have them

            if (isset($package['width']) && isset($package['height']) && isset($package['depth'])) {
                if (floatval($package['width']) > 0 && floatval($package['height']) > 0 && floatval($package['depth']) > 0) {
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
            if ($settings["automaticBooking"] == true) {
                $consignmentResult = $unifaun->bookConsignment($consignment, $transactionId);
            } else {
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


            $data['title'] = $shippingMethod->getTitle();


            $data['method'] = $shippingMethod->getShippingmethodId();


            $new_tracking_number = $consignment->getConsignmentNo();


            $data['number'] = $new_tracking_number;
            $data['track_number'] = $new_tracking_number;

            $data['parent_id'] = $shipment->getId();


            $track = $this->salesOrderShipmentTrackFactory->create()->addData($data);
            /* @var $track Mage_Sales_Model_Order_Shipment_Track */
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
}