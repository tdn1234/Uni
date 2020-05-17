<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnifaunConsignment\Renderer;

class Shipments extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * @var \Pcxpress\Unifaun\Helper\Consignment
     */
    protected $unifaunConsignmentHelper;

    /**
     * @var \Magento\Backend\Helper\Data
     */
    protected $backendHelper;

    /**
     * @var \Magento\Shipping\Model\Config
     */
    protected $shippingConfig;

    public function __construct(
        \Pcxpress\Unifaun\Helper\Consignment $unifaunConsignmentHelper,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Shipping\Model\Config $shippingConfig
    ) {
        $this->unifaunConsignmentHelper = $unifaunConsignmentHelper;
        $this->backendHelper = $backendHelper;
        $this->shippingConfig = $shippingConfig;
    }
    public function render(\Magento\Framework\DataObject $row)
    {
        $shipment       = $row; /** @var $shipment Mage_Sales_Model_Order_Shipment */
        $tracks         = $shipment->getAllTracks();
        $order          = $shipment->getOrder();        
        $trackingHtml = "<dl>";

        // Check if shipment not contain tracks
        if (!count($tracks)) {
            return Mage::helper('core')->__('This shipment does not contain any tracking information.');
        }

        $consignmentHelper = $this->unifaunConsignmentHelper;

        foreach ($tracks as $track) {
            $trackingHtml .= "<dt>";
            $trackingHtml .= "<strong>" . $track->getNumber() . " " . $this->getCarrierTitle($track->getCarrierCode()) . " " . $track->getTitle() . "</strong>";
            $trackingHtml .= "</dt>";


            $trackingHtml .= "<dd><a href=\"#\" onclick=\"popWin('" . $consignmentHelper->getConsignmentServiceUrl($track->getNumber()) . "', 'trackorder','width=800,height=600,resizable=yes,scrollbars=yes')\"><i class='fa fa-crosshairs'></i></a></dd>";

            // $trackingHtml .= "<dd><a href=\"#\" onclick=\"popWin('" . $this->helper('shipping')->getTrackingPopupUrlBySalesModel($track) . "', 'trackorder','width=800,height=600,resizable=yes,scrollbars=yes')\"><i class='fa fa-crosshairs'></i></a></dd>";

            $trackingHtml .= "<dd><a href=\"" . $this->backendHelper->getUrl("unifaun/adminhtml_unifaunConsignment/print", 
                array(
                    'consignment_nos' => $track->getNumber(), 
                    'orderIds' => $order->getId(),
                    'type' => \Pcxpress\Unifaun\Helper\Consignment::LABEL_TYPE_ID
                )
            ) . "\"><i class='fa fa-print'></i></a></dd>";            
        }
        $trackingHtml .= "</dl>";
        return $trackingHtml;
    }

    public function getCarrierTitle($code)
    {
        if ($carrier = $this->shippingConfig->getCarrierInstance($code)) {
            return $carrier->getConfigData('title');
        }
        else {
            return Mage::helper('core')->__('Custom Value');
        }
        return false;
    }
}