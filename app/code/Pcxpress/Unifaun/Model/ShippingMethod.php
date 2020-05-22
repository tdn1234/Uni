<?php
namespace Pcxpress\Unifaun\Model;

class ShippingMethod extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Pcxpress\Unifaun\Model\ResourceModel\ShippingMethod');
    }
}