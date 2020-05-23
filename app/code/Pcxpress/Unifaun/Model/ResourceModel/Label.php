<?php
namespace Pcxpress\Unifaun\Model\ResourceModel;

class Label extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('unifaun_labels', 'label_id');
    }
}
?>