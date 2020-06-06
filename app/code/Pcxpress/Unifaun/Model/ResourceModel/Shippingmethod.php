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
}