<?php
namespace Pcxpress\Unifaun\Model\ResourceModel;

class Shippingrate extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('unifaun_shippingrates', 'shippingrate_id');
    }
}
?>