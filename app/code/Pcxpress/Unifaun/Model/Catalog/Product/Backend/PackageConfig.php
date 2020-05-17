<?php
namespace Pcxpress\Unifaun\Model\Catalog\Product\Backend;


class PackageConfig
    extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{

    /**
     * @var \Pcxpress\Unifaun\Helper\Package
     */
    protected $unifaunPackageHelper;

    public function __construct(
        \Pcxpress\Unifaun\Helper\Package $unifaunPackageHelper
    ) {
        $this->unifaunPackageHelper = $unifaunPackageHelper;
    }
    public function beforeSave($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $packageValues = $object->getData($attrCode);
        $packageValues = $this->unifaunPackageHelper->getPackageValues($object, $attrCode);

        if ($packageValues) {
            $object->setData($attrCode, $packageValues);
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

        $packageConfigurations = $this->unifaunPackageHelper->setPackageData($object, $attrCode);

        $object->setData($attrCode, $packageConfigurations);

        return $this;
    }

   

}