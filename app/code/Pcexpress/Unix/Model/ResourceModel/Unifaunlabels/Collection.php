<?php

namespace Pcexpress\Unix\Model\ResourceModel\Unifaunlabels;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Pcexpress\Unix\Model\Unifaunlabels', 'Pcexpress\Unix\Model\ResourceModel\Unifaunlabels');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>