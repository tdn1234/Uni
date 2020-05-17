<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\Label\Renderer;

class OrderId extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer 
{

    /**
     * @var \Magento\Sales\Model\Order\ShipmentFactory
     */
    protected $salesOrderShipmentFactory;

    public function __construct(
        \Magento\Sales\Model\Order\ShipmentFactory $salesOrderShipmentFactory
    ) {
        $this->salesOrderShipmentFactory = $salesOrderShipmentFactory;
    }
    public function render(\Magento\Framework\DataObject $row)
    {    	
        $shippingId = $row->getShipmentId();
        $shipment = $this->salesOrderShipmentFactory->create()->load($shippingId);        
        return $shipment->getOrderId();
    }
}