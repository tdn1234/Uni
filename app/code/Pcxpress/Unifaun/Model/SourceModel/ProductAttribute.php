<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\SourceModel;

class ProductAttribute
{

    /**
     * @var \Magento\Eav\Model\EntityFactory
     */
    protected $eavEntityFactory;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory
     */
    protected $eavResourceModelEntityAttributeCollectionFactory;

    public function __construct(
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $eavResourceModelEntityAttributeCollectionFactory
    ) {
        $this->eavEntityFactory = $eavEntityFactory;
        $this->eavResourceModelEntityAttributeCollectionFactory = $eavResourceModelEntityAttributeCollectionFactory;
    }
    public function toOptionArray()
    {
        $entityTypeId = $this->eavEntityFactory->create()->setType('catalog_product')->getTypeId();
        $attributes = $this->eavResourceModelEntityAttributeCollectionFactory->create()->setEntityTypeFilter($entityTypeId);
        
        $result = array();
        $result[] = array("value" => "", "label" => __("Not used"));
        foreach ($attributes as $attribute) {
            $label = __($attribute->getFrontendLabel());
            if (!$label) {
                $label = $attribute->getAttributeCode();
            }
            $result[] = array("value" => $attribute->getAttributeCode(), "label" => $label);
        }
        return $result;
    }
}