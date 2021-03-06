<?php

namespace Pcxpress\Unifaun\Model\ResourceModel;

class Shippingmethod extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('unifaun_shippingmethods', 'shippingmethod_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $storeIds = $object->getData('store_ids');
        if (is_array($storeIds)) {
            $storeIds = implode(',', $storeIds);
            $object->setData('store_ids', $storeIds);
        }
        
        return parent::_afterSave($object); // TODO: Change the autogenerated stub
    }
}