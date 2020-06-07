<?php

namespace Pcxpress\Unifaun\Block\Adminhtml\Shippingrate\Grid\Renderer;

use Magento\Framework\DataObject;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;

class ShippingmethodName extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /** @var ShippingmethodFactory $shippingMethodFactory */
    protected $shippingMethodFactory;

    public function __construct(
        ShippingmethodFactory $shippingmethodFactory
    )
    {
        $this->shippingMethodFactory = $shippingmethodFactory;
    }

    /**
     * get shippingmethod name
     * @param  DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {
        $shippingmethodId = $row->getData('shippingmethod_id');

        $shippingmethod = $this->shippingMethodFactory->create()->load($shippingmethodId);
        return $shippingmethod->getTitle();
    }
}