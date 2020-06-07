<?php

namespace Pcxpress\Unifaun\Block\Adminhtml\Shippingrate\Edit\Renderer;

use Magento\Framework\DataObject;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;

class ShippingmethodArray extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
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
     * get shippingmethods name
     * @param  DataObject $row
     * @return array
     */
    public function render(DataObject $row)
    {
        $shippingmethodsArray = array();
        $shippingmethods = $this->shippingMethodFactory->create()->getCollection();
        foreach ($shippingmethods as $shippingmethod) {
            $shippingmethodsArray['value'] = $shippingmethod->getId();
            $shippingmethodsArray['label'] = $shippingmethod->getTitle();
        }

        var_dump($shippingmethodsArray);die;
        return $shippingmethodsArray;
    }
}