<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Model;

class Parcel
{
    protected $_packages = array();
    protected $_shippingMethod;
    protected $_entity;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $catalogProductFactory;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $configurableProductResourceModelProductTypeConfigurable;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    public function __construct(
        \Magento\Catalog\Model\ProductFactory $catalogProductFactory,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductResourceModelProductTypeConfigurable,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory
    )
    {
        $this->catalogProductFactory = $catalogProductFactory;
        $this->configurableProductResourceModelProductTypeConfigurable = $configurableProductResourceModelProductTypeConfigurable;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
    }

    private function getSimpleProductPackageConfigurations($child, $packageConfig, $_product)
    {

        $childProduct = $this->catalogProductFactory->create()->load($child->getProduct()->getId());

        $productPackageConfigurations = $childProduct->getData($packageConfig);

        if (count($productPackageConfigurations)) {
            return $productPackageConfigurations;
        } else {
            $arrayParentIds = $this->configurableProductResourceModelProductTypeConfigurable->getParentIdsByChild($_product->getId());
            $parentId = reset($arrayParentIds);
            $parentProduct = $this->catalogProductFactory->create()->load($parentId);
            $productPackageConfigurations = $parentProduct->getData($packageConfig);

            return $productPackageConfigurations;
        }
    }

    public function getParcels()
    {
        $shipment = $this->getEntity();
        $parcelsArray = array();
        $packages = array();
        $order = $this->getEntity()->getOrder();
        $packageConfig = $this->unifaunHelper->getPackageConfiguration($order->getStoreId());
        if ($packageConfig) {
            foreach ($shipment->getAllItems() as $_shipmentItem) {

                $orderItem = $_shipmentItem->getOrderItem();
                $_product = $orderItem->getProduct()->load($orderItem->getProductId());

                if ($orderItem->getProduct()->isVirtual() || ($orderItem->getParentItem() && !$orderItem->isShipSeparately())) {
                    continue;
                }

                if ($orderItem->getHasChildren() && !$orderItem->isShipSeparately()) {
                    foreach ($orderItem->getChildrenItems() as $child) {
                        if (!$child->getProduct()->isVirtual()) {
                            $productPackageConfigurations = $this->getSimpleProductPackageConfigurations($child, $packageConfig, $_product);
                        }

                    }
                } else {
                    $productPackageConfigurations = $_product->getData($packageConfig);
                }

                $packages = $this->getPackages($_shipmentItem, $productPackageConfigurations, $_product);

                $parcelsArray = $this->getParcelsArray($_shipmentItem, $productPackageConfigurations, $_product);
            }


        }

        if (count($packages) > 1) {
            $parcelUnified = array();
            $parcelUnification = $this->getUnifiedShippingMethod($packages);

            if ($parcelUnification) {
                if (array_key_exists('shippingMethodId', $parcelUnification) && array_key_exists('advice', $parcelUnification)) {
                    foreach ($boxes as $box) {
                        $parcel['shippingMethod'] = $parcelUnification['shippingMethodId'];
                        $parcel['advice'] = $parcelUnification['advice'];
                        $parcelUnified[] = $parcel;
                    }
                }
                if (count($parcelUnified)) {
                    $parcelsArray = $parcelUnified;
                }
            }
        }

        // If no boxes, set a default box
        if (!count($parcelsArray)) {
            $defaultWeight = 0;
            if ($this->getEntity()) {
                if ($this->getEntity() instanceof \Magento\Sales\Model\Order) {
                } elseif ($this->getEntity() instanceof \Magento\Sales\Model\Order\Shipment) {
                    $shipment = $this->getEntity();
                    /* @var $shipment Mage_Sales_Model_Order_Shipment */
                    foreach ($shipment->getAllItems() as $shipmentItem) {
                        /* @var $shipmentItem Mage_Sales_Model_Order_Shipment_Item */
                        $defaultWeight += floatval($shipmentItem->getWeight()) * floatval($shipmentItem->getQty());
                    }
                }
            }

            $parcelsArray[] = array(
                'weight' => 3,
                'width' => '',
                'height' => '',
                'depth' => '',
                'note' => '',
                'goodsType' => '',
                'shippingMethod' => '',
                'advice' => ''
            );

        }

        return $parcelsArray;
    }

    public function getPackages($_shipmentItem, $productPackageConfigurations, $_product)
    {
        $packages = [];

        if ($productPackageConfigurations) {

            $totalParcels = count($packageConfig);

            for ($k = 0; $k < $_shipmentItem->getQty(); $k++) {
                $parcelNum = 0;
                foreach ($productPackageConfigurations as $packageConfiguration) {
                    $packages[] = $packageConfiguration;
                    $parcelNum = $parcelNum + 1;

                    $parcelsArray[] = array(
                        'weight' => $packageConfiguration->getWeight(),
                        'width' => $packageConfiguration->getWidth(),
                        'height' => $packageConfiguration->getHeight(),
                        'depth' => $packageConfiguration->getDepth(),
                        'note' => $_product->getName() . ' (' . $parcelNum . 'of' . $totalParcels . ')',
                        'goodsType' => $packageConfiguration->getGoodsType(),
                        'shippingMethod' => $this->_getPackageConfigShippingMethod($packageConfiguration),
                        'advice' => $this->_getPackageConfigOption($packageConfiguration)
                    );
                }
            }

        }

        return $packages;

    }

    public function getParcelsArray($_shipmentItem, $productPackageConfigurations, $_product)
    {
        $parcels = [];

        if ($productPackageConfigurations) {

            $totalParcels = count($packageConfig);

            for ($k = 0; $k < $_shipmentItem->getQty(); $k++) {
                $parcelNum = 0;
                foreach ($productPackageConfigurations as $packageConfiguration) {
                    $parcelNum = $parcelNum + 1;

                    $parcels[] = array(
                        'weight' => $packageConfiguration->getWeight(),
                        'width' => $packageConfiguration->getWidth(),
                        'height' => $packageConfiguration->getHeight(),
                        'depth' => $packageConfiguration->getDepth(),
                        'note' => $_product->getName() . ' (' . $parcelNum . 'of' . $totalParcels . ')',
                        'goodsType' => $packageConfiguration->getGoodsType(),
                        'shippingMethod' => $this->_getPackageConfigShippingMethod($packageConfiguration),
                        'advice' => $this->_getPackageConfigOption($packageConfiguration)
                    );
                }
            }

        }

        return $parcels;

    }

    protected function _getPackageConfigShippingMethod(\Pcxpress\Unifaun\Model\PackageConfig $packageConfig)
    {
        $shippingMethodId = null;

        if ($packageConfig->getShippingMethod() === 'none') {
            // Get shipping method from order
            $order = $this->getEntity()->getOrder();
            $shippingMethodArray = explode("_", $order->getShippingMethod());
            $shippingMethodId = $shippingMethodArray[1];
        } else {
            $shippingMethodId = $packageConfig->getShippingMethod();

        }
        return $shippingMethodId;
    }

    protected function _getPackageConfigOption(\Pcxpress\Unifaun\Model\PackageConfig $packageConfig)
    {
        $packageConfigAdvice = null;
        if ($packageConfig->getAdvice() != 'none' || !strlen($packageConfig->getAdvice())) {
            $packageConfigAdvice = $packageConfig['advice'];
        }

        //Default Advice
        if (!$packageConfigAdvice) {
            $order = $this->getEntity()->getOrder();
            $packageConfigAdvice = $this->unifaunHelper->getDefaultAdviceType($order->getStoreId());
        }
        return $packageConfigAdvice;
    }

    public function getUnifiedShippingMethod(array $packages)
    {
        if (!count($packages)) {
            return null;
        }


        $packageUnification = array();
        $adviceUnification = array();

        foreach ($packages as $package) {

            $shippingMethod = $this->unifaunShippingMethodFactory->create()->load($package->getShippingMethod());
            if (!$shippingMethod->getUnificationEnable()) {
                return null;
            }

            if ($method->getUnificationPriority() == 0) {
                return null;
            }
            $packageUnification[$package->getShippingMethod()] = $method->getUnificationPriority();
            $adviceUnification[$package->getShippingMethod()] = $package->getAdvice();
        }

        $unifiedMethodArray = array_keys($packageUnification, min($packageUnification));

        if (is_array($unifiedMethodArray)) {
            $unifiedMethodId = reset($unifiedMethodArray);
        }
        if (!intval($unifiedMethodId)) {
            return null;
        }
        $shippingMethod = $this->unifaunShippingMethodFactory->create()->load($unifiedMethodId);

        if ($shippingMethod->getUnificationProductId()) {
            $unifiedShippingMethod = $this->unifaunShippingMethodFactory->create()->load($shippingMethod->getUnificationProductId());

            if ($unifiedShippingMethod) {
                $unifiedMethodId = $unifiedShippingMethod->getShippingmethodId();

                if ($unifiedShippingMethod->getDefaultAdvice()) {
                    $adviceUnification[$unifiedMethodId] = $unifiedShippingMethod->getDefaultAdvice();
                }
            }
        }

        $shippingMethodArray = array(
            'shippingMethodId' => $unifiedMethodId,
            'advice' => array_key_exists($unifiedMethodId, $adviceUnification) ? $adviceUnification[$unifiedMethodId] : 'none'
        );

        return $shippingMethodArray;
    }


    /**
     * @return \Magento\Sales\Model\AbstractModel
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * @param \Magento\Sales\Model\AbstractModel $entity
     * @return $this
     */
    public function setEntity(\Magento\Sales\Model\AbstractModel $entity)
    {
        $this->_entity = $entity;
        return $this;
    }
}