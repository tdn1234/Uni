<?php
namespace Pcxpress\Unifaun\Model\Catalog\Product\Backend;


class PackageConfiguration
    extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{

    /**
     * @var \Pcxpress\Unifaun\Model\PackageConfigurationFactory
     */
    protected $unifaunPackageConfigurationFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\PackageConfigurationFactory $unifaunPackageConfigurationFactory
    ) {
        $this->unifaunPackageConfigurationFactory = $unifaunPackageConfigurationFactory;
    }
    public function beforeSave($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $packageValues = $object->getData($attrCode);
        if (is_array($packageValues)) {
            $object->setData($attrCode, $this->serialize($packageValues));
        }

        return $this;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this|Mage_Eav_Model_Entity_Attribute_Backend_Abstract
     */
    public function afterLoad($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $packageValues = $object->getData($attrCode);
        $packageConfigurations = $this->unserialize($packageValues);
        $object->setData($attrCode, $packageConfigurations);
        return $this;
    }

    /**
     * @param $packageValues
     * @return array
     */
    protected function _convertArrayToModels($packageValues)
    {
        $packageConfigurations = array();

        if (is_array($packageValues)) {
            foreach ($packageValues as $packageValue) {
                if (is_array($packageValue)) {
                    $packageConfiguration = $this->unifaunPackageConfigurationFactory->create(); /* @var $packageConfiguration Pcxpress_Unifaun_Model_PackageConfiguration */
                    $packageConfiguration->setWidth(floatval($this->_getValue('width', $packageValue)));
                    $packageConfiguration->setHeight(floatval($this->_getValue('height', $packageValue)));
                    $packageConfiguration->setDepth(floatval($this->_getValue('depth', $packageValue)));
                    $packageConfiguration->setWeight(floatval($this->_getValue('weight', $packageValue)));
                    $packageConfiguration->setGoodsType($this->_getValue('goodsType', $packageValue));
                    $packageConfiguration->setShippingMethod($this->_getValue('shippingMethod', $packageValue));
                    $packageConfiguration->setAdvice($this->_getValue('advice', $packageValue));
                    $packageConfigurations[] = $packageConfiguration;
                }
            }
        }

        return $packageConfigurations;
    }

    /**
     * @param array $packageConfigurations
     * @return mixed|string|void
     */
    protected function serialize(array $packageConfigurations)
    {
        $values = array();
        foreach ($packageConfigurations as $package) { /* @var $package Pcxpress_Unifaun_Model_PackageConfiguration  */
            $values[] = array(
                'width' => $package->getWidth(),
                'height' => $package->getHeight(),
                'depth' => $package->getDepth(),
                'weight' => $package->getWeight(),
                'goodsType' => $package->getGoodsType(),
                'shippingMethod' => $package->getShippingMethod(),
                'advice' => $package->getAdvice()
            );
        }
        return json_encode($values);
    }

    /**
     * @param $values
     * @return array
     */
    protected function unserialize($values)
    {
        if (!$values) {
            return array();
        }

        if (is_string($values)) {
            $array = json_decode($values, true);
            if (!is_array($array)) {
                return array();
            }

            return $this->_convertArrayToModels($array);
        }

        return array();
    }

    protected function _getValue($key, array $array)
    {
        if (array_key_exists($key, $array)) {
            if ($array[$key]) {
                return $array[$key];
            }
        }
        return null;
    }

}