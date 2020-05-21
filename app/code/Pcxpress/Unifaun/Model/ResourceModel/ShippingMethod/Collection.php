<?php

namespace Pcxpress\Unifaun\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Pcxpress\Unifaun\Model\ShippingMethod', 'Pcxpress\Unifaun\Model\ResourceModel\ShippingMethod');
        $this->_map['fields']['shippingmethod_id'] = 'main_table.shippingmethod_id';
    }

}
?>