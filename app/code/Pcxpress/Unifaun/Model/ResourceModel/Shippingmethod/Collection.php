<?php

namespace Pcxpress\Unifaun\Model\ResourceModel\Shippingmethod;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Pcxpress\Unifaun\Model\Shippingmethod', 'Pcxpress\Unifaun\Model\ResourceModel\Shippingmethod');
//        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>