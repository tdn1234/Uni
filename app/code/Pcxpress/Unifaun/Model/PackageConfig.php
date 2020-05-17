<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 *
 * @method double getWidth()
 * @method Pcxpress_Unifaun_Model_PackageConfiguration setWidth($width)
 * @method double getHeight()
 * @method Pcxpress_Unifaun_Model_PackageConfiguration setHeight($height)
 * @method double getDepth()
 * @method Pcxpress_Unifaun_Model_PackageConfiguration setDepth($depth)
 * @method double getWeight()
 * @method Pcxpress_Unifaun_Model_PackageConfiguration setWeight($weight)
 * @method string getGoodsType()
 * @method Pcxpress_Unifaun_Model_PackageConfiguration setGoodsType($goodsType)
 * @method string getShippingMethod()
 * @method Pcxpress_Unifaun_Model_PackageConfiguration setShippingMethod($shippingMethod)
 * @method string getAdvice()
 * @method Pcxpress_Unifaun_Model_PackageConfiguration setAdvice($advice)
 */

namespace Pcxpress\Unifaun\Model;

class PackageConfig1 extends \Magento\Framework\DataObject
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

}