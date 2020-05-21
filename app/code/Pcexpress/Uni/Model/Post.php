<?php
namespace Pcexpress\Uni\Model;

class Post extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Pcexpress\Uni\Model\ResourceModel\Post');
    }
}
?>