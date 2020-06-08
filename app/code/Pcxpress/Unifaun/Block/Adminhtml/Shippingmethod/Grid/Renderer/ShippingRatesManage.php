<?php

namespace Pcxpress\Unifaun\Block\Adminhtml\Shippingmethod\Grid\Renderer;

use Composer\Util\Url;
use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;

class ShippingRatesManage extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $_urlBuilder;

    public function __construct(
        UrlInterface $_urlBuilder
    )
    {
        $this->_urlBuilder = $_urlBuilder;
    }

    /**
     * get shippingmethod name
     * @param  DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {
        $a = '<a href="' . $this->_urlBuilder->getUrl('unifaun/shippingrate', array('shippingmethod_id' => $row->getId())) . '">Manage Rates</a>';
        return $a;
    }
}