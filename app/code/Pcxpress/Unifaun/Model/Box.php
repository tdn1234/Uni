<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Model;

class Box
{
    protected $_packages = array();
    protected $_shippingMethod;
    protected $_entity;

    const ATTRIBUTE_PACKAGE_CONFIGURATION   = "carriers/unifaun/attribute_package_configuration";
    const ATTRIBUTE_advice_default          = "carriers/unifaun/advice_type";

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $configurableProductResourceModelProductTypeConfigurable;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $catalogProductFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductResourceModelProductTypeConfigurable,
        \Magento\Catalog\Model\ProductFactory $catalogProductFactory,
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configurableProductResourceModelProductTypeConfigurable = $configurableProductResourceModelProductTypeConfigurable;
        $this->catalogProductFactory = $catalogProductFactory;
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
    }
    public function getNumberOfBoxes()
    {
        $shipment                       = $this->getEntity();
        $boxes                          = array();
        $packages                       = array();
        $attributePackageConfiguration  = $this->scopeConfig->getValue(self::ATTRIBUTE_PACKAGE_CONFIGURATION, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $shipment->getOrder()->getStoreId());
        if ($attributePackageConfiguration) {
            foreach ($shipment->getAllItems() as $n => $shipmentItem) { /* @var $shipmentItem Mage_Sales_Model_Order_Shipment_Item */
                $item       = $shipmentItem->getOrderItem(); /* @var $item Mage_Sales_Model_Order_Item */
                $product    = $item->getProduct()->load($item->getProductId()); /** @var $product Mage_Catalog_Model_Product */
                if ($item->getProduct()->isVirtual() || ($item->getParentItem() && !$item->isShipSeparately())) {
                    continue;
                }
                if ($item->getHasChildren() && !$item->isShipSeparately()) {
                    foreach ($item->getChildrenItems() as $child) { /* @var $child Mage_Sales_Model_Order_Item */
                        if (!$child->getProduct()->isVirtual()) {
                            $childProduct               = $item->getProduct()->load($child->getProduct()->getId());
                            $packageConfigurations      = $childProduct->getData($attributePackageConfiguration);
                            if (!count($packageConfigurations)) {
                                // We have no configuration for child product, try to fetch configuration from parent
                                $parentIds              = $this->configurableProductResourceModelProductTypeConfigurable->getParentIdsByChild($product->getId());
                                $parentId               = reset($parentIds); // Get the first value from array
                                $parentProduct          = $this->catalogProductFactory->create()->load($parentId);
                                $packageConfigurations  = $parentProduct->getData($attributePackageConfiguration);
                            }
                            for ($n = 0; $n < $shipmentItem->getQty(); $n++) {
                                $boxId = 0;
                                foreach ($packageConfigurations as $packageConfiguration) { /* @var $packageConfiguration Pcxpress_Unifaun_Model_PackageConfiguration */
                                    $packages[]             = $packageConfiguration;
                                    $boxes[] = array(
                                        'weight'            => $packageConfiguration->getWeight(),
                                        'width'             => $packageConfiguration->getWidth(),
                                        'height'            => $packageConfiguration->getHeight(),
                                        'depth'             => $packageConfiguration->getDepth(),
                                        'note'              => $childProduct->getName() . ' (' . ++$boxId . '/' . count($packageConfigurations) . ')',
                                        'goodsType'         => $packageConfiguration->getGoodsType(),
                                        'shippingMethod'    => $this->_getShippingMethodForPackageConfiguration($packageConfiguration),
                                        'advice'            => $this->_getAdviceForPackageConfiguration($packageConfiguration)
                                    );
                                }
                            }

                        }
                    }
                } else {
                    $packageConfigurations = $product->getData($attributePackageConfiguration);
                    if ($packageConfigurations) {
                        for ($n = 0; $n < $shipmentItem->getQty(); $n++) {
                            $boxId = 0;
                            foreach ($packageConfigurations as $packageConfiguration) { /* @var $packageConfiguration Pcxpress_Unifaun_Model_PackageConfiguration */
                                $packages[]             = $packageConfiguration;
                                $boxes[] = array(
                                    'weight'            => $packageConfiguration->getWeight(),
                                    'width'             => $packageConfiguration->getWidth(),
                                    'height'            => $packageConfiguration->getHeight(),
                                    'depth'             => $packageConfiguration->getDepth(),
                                    'note'              => $product->getName() . ' (' . ++$boxId . '/' . count($packageConfigurations) . ')',
                                    'goodsType'         => $packageConfiguration->getGoodsType(),
                                    'shippingMethod'    => $this->_getShippingMethodForPackageConfiguration($packageConfiguration),
                                    'advice'            => $this->_getAdviceForPackageConfiguration($packageConfiguration)
                                );
                            }
                        }
                    }
                }
            }
        }

        if (count($packages) > 1) {
            // Try to consolidate this shipment
            $boxConsolidation   = $this->getConsolidatedShippingMethod($packages);
            $boxConsolidated    = array();
            if ($boxConsolidation) {
                if (array_key_exists('shippingMethodId', $boxConsolidation) && array_key_exists('advice', $boxConsolidation)) {
                    // The consolidation was successful, now we need to rewrite the boxes to reflect the consolidation.
                    foreach ($boxes as $box) {
                        $box['shippingMethod']  = $boxConsolidation['shippingMethodId'];
                        $box['advice']          = $boxConsolidation['advice'];
                        $boxConsolidated[]      = $box;
                    }
                }
                if (count($boxConsolidated)) {
                    $boxes = $boxConsolidated;
                }
            }
        }

        // If no boxes, set a default box
        if (!count($boxes)) {
            $defaultWeight = 0;
            if ($this->getEntity()) {
                if ($this->getEntity() instanceof \Magento\Sales\Model\Order) {
                    // @todo
                } elseif ($this->getEntity() instanceof \Magento\Sales\Model\Order\Shipment) {
                    $shipment = $this->getEntity(); /* @var $shipment Mage_Sales_Model_Order_Shipment */
                    foreach ($shipment->getAllItems() as $item) { /* @var $item Mage_Sales_Model_Order_Shipment_Item */
                        $defaultWeight += floatval($item->getWeight()) * floatval($item->getQty());
                    }
                }
            }
            $boxes[] = array(
                'weight'            => $defaultWeight,
                'width'             => '',
                'height'            => '',
                'depth'             => '',
                'note'              => '',
                'goodsType'         => '',
                'shippingMethod'    => '',
                'advice'            => ''
            );
        }

        return $boxes;
    }

    public function getConsolidatedShippingMethod(array $packages)
    {
        if (!count($packages)) {
            return null;
        }

        // Stage 1 consolidation
        $packageConsolidation   = array();
        $adviceConsolidation    = array();

        foreach ($packages as $package) { /** @var $package Pcxpress_Unifaun_Model_PackageConfiguration */
            $method = $this->unifaunShippingMethodFactory->create()->load($package->getShippingMethod()); /* @var $method Pcxpress_Unifaun_Model_ShippingMethod */
            // We will have to abort if now all the methods are allowed to be consolidated
            if (!$method->getConsolidationEnable()) {
                return null;
            }
            if ($method->getConsolidationPriority() == 0) {
                return null;
            }
            $packageConsolidation[$package->getShippingMethod()] = $method->getConsolidationPriority();
            $adviceConsolidation[$package->getShippingMethod()]  = $package->getAdvice();

        }

        $consolidatedMethodArray = array_keys($packageConsolidation, min($packageConsolidation));

        if (is_array($consolidatedMethodArray)) {
            $consolidatedMethodId = reset($consolidatedMethodArray);
        }
        if (!intval($consolidatedMethodId)) {
            return null;
        }

        // Stage 2 consolidation

        // Check if the resulting method after consolidation has support for level 2 consolidation
        $shippingMethod = $this->unifaunShippingMethodFactory->create()->load($consolidatedMethodId); /* @var $shippingMethod Pcxpress_Unifaun_Model_ShippingMethod */
        if ($shippingMethod->getConsolidationProductId()) {
            // Do the stage 2 consolidation.
            $consolidatedShippingMethod = $this->unifaunShippingMethodFactory->create()->load($shippingMethod->getConsolidationProductId()); /* @var $consolidatedShippingMethod Pcxpress_Unifaun_Model_ShippingMethod */
            if ($consolidatedShippingMethod) {
                $consolidatedMethodId = $consolidatedShippingMethod->getShippingmethodId();
                // Try to get default advice for method
                if ($consolidatedShippingMethod->getDefaultAdvice()) {
                    $adviceConsolidation[$consolidatedMethodId] = $consolidatedShippingMethod->getDefaultAdvice();
                }
            }
        }

        $shippingMethodArray = array(
            'shippingMethodId' => $consolidatedMethodId,
            'advice' => array_key_exists($consolidatedMethodId, $adviceConsolidation) ? $adviceConsolidation[$consolidatedMethodId] : 'none'
        );

        return $shippingMethodArray;
    }

    protected function _getShippingMethodForPackageConfiguration(\Pcxpress\Unifaun\Model\PackageConfiguration $packageConfiguration)
    {
        $shippingMethodId = null;
        // Check if this configuration have a shipping method
        if ($packageConfiguration->getShippingMethod() !== 'none') {
            $shippingMethodId = $packageConfiguration->getShippingMethod();
        } else {
            // Get shipping method from order
            $order = $this->getEntity()->getOrder();
            $shippingMethodParts = explode("_", $order->getShippingMethod());
            $shippingMethodId = $shippingMethodParts[1];
        }

        return $shippingMethodId;
    }

    protected function _getAdviceForPackageConfiguration(\Pcxpress\Unifaun\Model\PackageConfiguration $packageConfiguration)
    {
        $configurationAdvice = null;
        if ($packageConfiguration->getAdvice() != 'none' || !strlen($packageConfiguration->getAdvice())) {
            $configurationAdvice = $packageConfiguration['advice'];
        }

        // If no advice is selected, use the selected default.
        if (!$configurationAdvice) {
            $configurationAdvice = $this->scopeConfig->getValue(self::ATTRIBUTE_advice_default, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->getOrder()->getStoreId());
        }

        return $configurationAdvice;
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

    public function getOrder()
    {
        return $this->getEntity()->getOrder();
    }
}