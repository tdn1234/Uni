<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Helper;

class Package extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Pcxpress\Unifaun\Model\PackageConfigFactory
     */
    protected $unifaunPackageConfigFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Pcxpress\Unifaun\Model\PackageConfigFactory $unifaunPackageConfigFactory
    ) {
        $this->unifaunPackageConfigFactory = $unifaunPackageConfigFactory;
        parent::__construct(
            $context
        );
    }


	public function getPackageValues($object, $attrCode){
		$packageValues = $object->getData($attrCode);
         if (!is_array($packageValues)) {
            $packageValues = array();
        }
		return $this->serialize($packageValues);
	}

	public function setPackageData($object, $attrCode){

        $packageValues = $object->getData($attrCode);

        return $this->unserialize($packageValues);
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
                    $packageConfiguration = $this->unifaunPackageConfigFactory->create();
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
        foreach ($packageConfigurations as $package) { 
					if(is_array($package)){
						$values[] = array(
                'width' => $package['width'],
                'height' => $package['height'],
                'depth' => $package['depth'],
                'weight' => $package['weight'],
                'goodsType' => $package['goodsType'],
                'shippingMethod' => $package['shippingMethod'],
                'advice' => $package['advice']
            );
					}else{
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
        }
        return json_encode($values);
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

}