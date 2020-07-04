<?php

namespace Pcxpress\Unifaun\Model\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order;
use Pcxpress\Unifaun\Model\Carrier\Unifaun;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;
use Pcxpress\Unifaun\Helper\Data;
use Pcxpress\Unifaun\Model\Parcel;
use Pcxpress\Unifaun\Helper\ErrorMessage;
use Pcxpress\Unifaun\Helper\AddConsigment;

class AddConsignment implements \Magento\Framework\Event\ObserverInterface
{

    const RETURN_CONSIGNMENT = 'return_consignment';
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment\Data
     */
    protected $consignmentData;

    /** @var ShippingmethodFactory $shippingmethodFactory */
    protected $shippingmethodFactory;

    /** @var Data $unifaunHelper */
    protected $unifaunHelper;

    /** @var AddConsigment$addConsignmentHelper
     */
    protected $addConsignmentHelper;


    /** @var Parcel $parcelModel */
    protected $parcelModel;

    /** @var ErrorMessage $messageManager */
    protected $messageManager;

    public function __construct(
        \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Consignment\Data $consignmentData,
        RequestInterface $request,
        ShippingmethodFactory $shippingmethodFactory,
        Data $unifaunHelper,
        Parcel $parcelModel,
        ErrorMessage $messageManager,
        AddConsigment $addConsignmentHelper
    )
    {
        $this->request = $request;
        $this->consignmentData = $consignmentData;
        $this->shippingmethodFactory = $shippingmethodFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->addConsignmentHelper = $addConsignmentHelper;
        $this->parcelModel = $parcelModel;
        $this->messageManager = $messageManager;
    }

    public function execute(
        \Magento\Framework\Event\Observer $observer
    )
    {
        // $this->updateTrackingCode('153', '555553464356');

        $returnedShipment = ($this->request->getParam('submit') === self::RETURN_CONSIGNMENT) ? true : false;


//        var_dump($this->request->getParams());

//        var_dump($this->request->getParams());

        $shipment = $observer->getShipment();
//        var_dump($shipment->getData());die;
//
//        var_dump($shipment);die;


        $consignmentData = $this->consignmentData;

//        var_dump($consignmentData->getData());die;


        // var_dump(get_class_methods($consignmentData));

        // var_dump(Mage::app()->getRequest()->getParam('parcel'));die;

        // var_dump($consignmentData->getData());die;

        // if (!count($consignment->getData())) {

        // }


        $parcels = null;

        $params = false;

        if ($consignmentData->getData('custom_booking')) {
            // die('cccc');
            $parcels = $consignmentData->getData('parcel');

            $params = $consignmentData->getData('params');
        } else if (!$shipment->isObjectNew()) {
            $parcels = $this->request->getParam('parcel');
            // return false;
        } else {
            $parcels = $this->request->getParam('parcel');


            $params = $this->request->getParams();
        }

//        var_dump($params);
//        var_dump($parcels);die;

        if ($params === false) {
            $params = $this->request->getParams();
        }

        if ($parcels == null) {
            $parcels = $this->parcelModel->setEntity($shipment)->getParcels();

            if (!count($parcels)) {
                $parcels[] = array('weight' => $this->_getShipmentWeight($shipment));
            }
        } elseif (!is_array($parcels)) {
            throw new \Exception("Parcels must be an array collection.");
        }

        $packages = $this->_getPackagesByMethodAndAdvice($parcels);

        /** @var Order $order */
        $order = $shipment->getOrder();

        $this->updateOrderShipment($order);

        if (!$order->getId()) {
            throw new \Exception('Order not found.');
        }

        $carrierCode = '';
        $failedPackages = array();

        if (!$carrierCode) {
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

                        $this->messageManager->_logShipmentErrorsMessage('Invalid method', $shipment);


                        continue;
                    }


                    $methodId = (int)$method[1];
                }


                $method = $this->shippingmethodFactory->create()->load($methodId);

//                var_dump($method->isObjectNew());
//                var_dump($method->getData());die;

                if ($method->isObjectNew()) {
                    $package = null;

                    if (isset($_package['packages'][0])) {
                        $package = $_package['packages'][0];
                    }

                    $this->messageManager->_logShipmentErrorsMessage('Shipping Method dose not exists.', $shipment);


                    continue;
                }


                $adviceType = null;


                if (array_key_exists('advice', $_package)) {
                    $adviceType = $_package['advice'];
                }


                if ($method->getLabelOnly()) {
                    continue;
                } elseif ($method->getNoBooking()) {
                    continue;
                }


                $settings = array();


                $orderNumner = isset($params['unifaun_order_number']) ? $params['unifaun_order_number'] : '';


                $consigneeReference = isset($params['unifaun_consignee_reference']) ? $params['unifaun_consignee_reference'] : '';


                $consignmentNo = (isset($params['unifaun_consignment_number'])) ? $params['unifaun_consignment_number'] : '';


                $settings['reference'] = array(
                    'order_number' => $orderNumner,
                    'consignee_reference' => $consigneeReference,
                    'consignment_number' => $consignmentNo
                );

                if (isset($params['unifaun_advice_contact'])) {
                    $adviceTypeContact = $params['unifaun_advice_contact'];
                } else {
                    $adviceTypeContact = $this->addConsignmentHelper->_getShipmentConfig($shipment, 'unifaun_advice_contact');
                }


                if (isset($params['unifaun_advice_mobile'])) {
                    $adviceTypeMobile = $this->unifaunHelper->sanitizePhoneNumber($params['unifaun_advice_mobile']);
                } else {
                    $adviceTypeMobile = $this->unifaunHelper->sanitizePhoneNumber($this->_getShipmentConfig($shipment, 'unifaun_advice_mobile'));
                }


                if (isset($params['unifaun_advice_email'])) {
                    $adviceTypeEmail = $params['unifaun_advice_email'];
                } else {
                    return;


                    $adviceTypeEmail = $this->addConsignmentHelper->_getSettingsForShipment($shipment, 'unifaun_advice_email');
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
                    $automaticBooking = $this->addConsignmentHelper->_getShipmentConfig($shipment, 'unifaun_automatic_booking');
                }

                $settings['advice'] = array(
                    "type" => $adviceType,
                    "mobile" => $adviceTypeMobile,
                    "contact" => $adviceTypeContact,
                    "email" => $adviceTypeEmail,
                    "fax" => $adviceTypeFax,
                    "phone" => $adviceTypePhone);

                $settings['automaticBooking'] = $automaticBooking;
                if ($automaticBooking && $params['unifaun_pickup'] == "Y") {
                    $settings['pickup'] = array(
                        "date" => $params['unifaun_pickup_date'],
                        "earliest" => trim($params['unifaun_pickup_earliest']),
                        "latest" => trim($params['unifaun_pickup_latest']),
                        "instruction" => null,
                        "location" => null,
                    );
                }
                if ($params['unifaun_cod'] == "Y") {
                    $settings['cod'] = array(
                        "amount" => $params['unifaun_cod_amount'],
                        "currency" => $params['unifaun_cod_currency'],
                        "paymentMethod" => $params['unifaun_cod_paymentmethod'],
                        "accountNo" => $params['unifaun_cod_accountno'],
                        "reference" => $params['unifaun_cod_reference'],
                    );
                }

                if ($params['unifaun_pickup_location']) {
                    $settings['pickup_address'] = $params['unifaun_pickup_location'];
                }

                if (isset($params['insurance']) && $params['insurance'] == 'Y') {
                    $settings['insurance'] = array(
                        'amount' => (isset($params['insurance_amount'])) ? $params['insurance_amount'] : 0,
                        'currency' => (isset($params['unifaun_cod_currency'])) ? $params['unifaun_cod_currency'] : 'SEK'
                    );
                }


                $firstParcel = reset($parcels);

                $shippingMethod = (isset($firstParcel['shippingMethod'])) ? $firstParcel['shippingMethod'] : 0;

                $shippingMethod = $this->shippingmethodFactory->create()->load($shippingMethod);


                if ($shippingMethod && $shippingMethod->getData('shipping_service') == \Pcxpress\Unifaun\Helper\Data::WEBTA_SHIPPING_ID) {


                    if ($method->getMultipleParcels()) {
                        if (!$this->addConsignmentHelper->setShippingBooking($_package['packages'], $shipment, $method, $settings, $returnedShipment)) {
                            $failedPackages[] = $_package['packages'];


                            continue;
                        }
                    } else {
                        foreach ($_package['packages'] as $package) {
                            $packages = array($package); // _createShippingBooking() want package argument as array.


                            if (!$this->addConsignmentHelper->setShippingBooking($packages, $shipment, $method, $settings, $returnedShipment)) {
                                $failedPackages[] = array();
                                continue;
                            }

                        }
                    }
                }// end WEBTA_SHIPPING_ID condition

            }
        }

        $firstParcel = reset($parcels);

        $shippingMethod = (isset($firstParcel['shippingMethod'])) ? $firstParcel['shippingMethod'] : 0;

        $shippingMethod = $this->shippingmethodFactory->create()->load($shippingMethod);


        $order->setData('template_method_data', $shippingMethod->getData());

        var_dump($shippingMethod->getData());die;


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


    public function updateOrderShipment(Order $order)
    {
        $parcels = $this->request->getParam('parcel');

        if (!count($parcels)) {
            return;
        }

        $method = explode("_", $order->getShippingMethod());

        $methodId = (int)$method[1];

        $method = $this->shippingmethodFactory->create()->load($methodId);

        $unifaunCode = \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE;

        $newMethodId = false;

        $newMethodDesc = '';

        $updated = false;


        if (count($parcels)) {
            foreach ($parcels as $parcel) {
                if ($parcel['shippingMethod'] && $parcel['shippingMethod'] != $method->getId()) {
                    $newMethodId = $unifaunCode . '_' . $parcel['shippingMethod'];

                    $newMethod = $this->shippingmethodFactory->create()->load($parcel['shippingMethod']);

                    $newMethodDesc = $newMethod->getData('title');
                }
            }
        }


        if ($newMethodId) {
            $order->setShippingMethod($newMethodId);

            $updated = true;
        }

        if ($newMethodDesc) {
            $order->setShippingDescription($newMethodDesc);
            $updated = true;
        }

        if ($updated) {
            $order->save();
        }
        return;
    }
}