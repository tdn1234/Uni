<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class GoodsItem extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
    protected $_namespace = "http://www.spedpoint.com/consignment/types/v1_0";
    
       /**
     * Package Ids
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PackageIds $value
     */
    public function setPackageIds(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PackageIds $value)
    {
        $this->_properties['PackageIds'] = $value;
    }
        /**
     * Dangerous Goods
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\DangerousGoods $value
     */
    public function setDangerousGoods($value)
    {
        $this->_properties['DangerousGoods'] = $value;
    }

    /**
     * Number of packages for current goods item
     * @param integer $value
     */
    public function setNoOfPackages($value)
    {
        $this->_properties['noOfPackages'] = $value;
    }

  

    /**
     * Weight of item (kg)
     * @param float $value
     */
    public function setWeight($value)
    {
        $this->_properties['weight'] = $value;
    }



    /**
     * Length (cm)
     * @param float $value
     */
    public function setLength($value)
    {
        $this->_properties['length'] = $value;
    }
  

    public function setWidth($value)
    {
        $this->_properties['width'] = $value;
    }

    /**
     * Height (cm)
     * @param float $value
     */
    public function setHeight($value)
    {
        $this->_properties['height'] = $value;
    }


    /**
     * Loading Metres (m)
     * @param float $value
     */
    public function setLoadingMetres($value)
    {
        $this->_properties['loadingMetres'] = $value;
    }

    /**
     * Pallet Space
     * @param string $value
     */
    public function setPalletSpace($value)
    {
        $this->_properties['palletSpace'] = $value;
    }

    /**
     * Package Types depending on carrier and product. Default value if not sent in
     * PK = unspecified package type
     * @param string $value String 1..3
     */
    public function setPackageType($value)
    {
        $this->_properties['packageType'] = $value;
    }




    /**
     * Tag
     * @param string $value
     */
    public function setTag($value)
    {
        $this->_properties['tag'] = $value;
    }


    /**
     * Goods Type
     * @param string $value
     */
    public function setGoodsType($value)
    {
        $this->_properties['goodsType'] = $value;
    }



    /**
     * Goods Value
     * @param float $value
     */
    public function setGoodsValue($value)
    {
        $this->_properties['goodsValue'] = $value;
    }



    /**
     * Goods Value Currency
     * @param float $value
     */
    public function setGoodsValueCurrency($value)
    {
        $this->_properties['goodsValueCurrency'] = $value;
    }


    /**
     * Net weight
     * @param float $value
     */
    public function setNetWeight($value)
    {
        $this->_properties['netWeight'] = $value;
    }


    /**
     * Weight unit (kg, lb, stone, oz, mg, g, t)
     * @param string $value
     */
    public function setWeightUnit($value)
    {
        $this->_properties['weightUnit'] = $value;
    }


    /**
     * Volume unit (m3, ft3, dm3, cm3)
     * @param string $value
     */
    public function setVolumeUnit($value)
    {
        $this->_properties['volumeUnit'] = $value;
    }

 
   
    /**
     * Lenght unit (m, cm, mm, ft)
     * @param string $value
     */
    public function setLengthUnit($value)
    {
        $this->_properties['lengthUnit'] = $value;
    }

   /**
     * Get Package Ids
     * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\PackageIds
     */
    public function getPackageIds()
    {
        return (isset($this->_properties['PackageIds']))? $this->_properties['PackageIds'] : '';
    }



   /**
     * Get Dangerous Goods
     * @return \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\DangerousGoods
     */
    public function getDangerousGoods()
    {
        return (isset($this->_properties['DangerousGoods']))? $this->_properties['DangerousGoods'] : '';
    }



     public function getNoOfPackages()
    {
        return (isset($this->_properties['noOfPackages']))? $this->_properties['noOfPackages'] : '';
    }

     /**
     * Weight of item (kg)
     * @return float
     */
    public function getWeight()
    {
        return (isset($this->_properties['weight']))? $this->_properties['weight'] : '' ;
    }

       /**
     * Get Length (cm)
     * @return float
     */
    public function getLength()
    {
        return (isset($this->_properties['length']))? $this->_properties['length'] : '';
    }

     public function getLengthUnit()
    {
        return (isset($this->_properties['lengthUnit']))? $this->_properties['lengthUnit'] : '';
    }


    public function getVolumeUnit()
    {
        return (isset($this->_properties['volumeUnit']))? $this->_properties['volumeUnit'] : '';
    }

      public function getWidth()
    {
        return (isset($this->_properties['width']))? $this->_properties['width'] : '';
    }

        public function getGoodsType()
    {
        return (isset($this->_properties['goodsType']))? $this->_properties['goodsType'] : '';
    }

        public function getNetWeight()
    {
        return (isset($this->_properties['netWeight']))? $this->_properties['netWeight'] : '';
    }

        public function getWeightUnit()
    {
        return (isset($this->_properties['weightUnit']))? $this->_properties['weightUnit'] : '';
    }

        public function getGoodsValue()
    {
        return (isset($this->_properties['goodsValue']))? $this->_properties['goodsValue'] : '';
    }

        public function getGoodsValueCurrency()
    {
        return (isset($this->_properties['goodsValueCurrency']))? $this->_properties['goodsValueCurrency'] : '';
    }

    public function getLoadingMetres()
    {
        return (isset($this->_properties['loadingMetres']))? $this->_properties['loadingMetres'] : '';
    }

        public function getHeight()
    {
        return (isset($this->_properties['height']))? $this->_properties['height'] : '';
    }

        public function getPalletSpace()
    {
        return (isset($this->_properties['palletSpace']))? $this->_properties['palletSpace'] : '';
    }

       public function getPackageType()
    {
        return (isset($this->_properties['packageType']))? $this->_properties['packageType'] : '';
    }
    
    public function getTag()
    {
        return (isset($this->_properties['tag']))? $this->_properties['tag'] : '';
    }
    
}