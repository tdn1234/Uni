<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnifaunMatrix\Renderer;

class ConsolidationProduct extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * @var \Pcxpress\Unifaun\Model\ShippingMethodFactory
     */
    protected $unifaunShippingMethodFactory;

    public function __construct(
        \Pcxpress\Unifaun\Model\ShippingMethodFactory $unifaunShippingMethodFactory
    ) {
        $this->unifaunShippingMethodFactory = $unifaunShippingMethodFactory;
    }
    public function render(\Magento\Framework\DataObject $row)
    {
        /** @var $row Pcxpress_Unifaun_Model_ShippingMethod */
        if (!$row->getConsolidationProductId() || !intval($row->getConsolidationProductId())) {
            return '-';
        }
        $shippingMethodTitle = '-';
        $shippingMethod = $this->unifaunShippingMethodFactory->create()->load($row->getConsolidationProductId()); /* @var $shippingMethod Pcxpress_Unifaun_Model_ShippingMethod */
        if ($shippingMethod && $shippingMethod->getTitle()) {
            $shippingMethodTitle = $shippingMethod->getTitle();
        }

        return $shippingMethodTitle;
    }

}