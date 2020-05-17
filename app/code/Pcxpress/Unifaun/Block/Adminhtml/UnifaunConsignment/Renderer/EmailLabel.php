<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnifaunConsignment\Renderer;

class EmailLabel extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * @var \Pcxpress\Unifaun\Helper\Consignment
     */
    protected $unifaunConsignmentHelper;

    public function __construct(
        \Pcxpress\Unifaun\Helper\Consignment $unifaunConsignmentHelper
    ) {
        $this->unifaunConsignmentHelper = $unifaunConsignmentHelper;
    }
    public function render(\Magento\Framework\DataObject $row)
    {
        $consignmentHelper = $this->unifaunConsignmentHelper;
        
        $consignment_id = '';
        foreach($row->getAllTracks() as $track){
            $consignment_id = $track->getData('track_number');
        }
        $order_id = $row->getOrderId();
        $token = $consignmentHelper->generateToken($consignment_id, $order_id);

        $url = Mage::getBaseUrl(\Magento\Store\Model\Store::URL_TYPE_WEB) . 'printconsignment?consignment_id=%s&order_id=%s&token=%s';
        $url = sprintf($url, $consignment_id, $row->getOrderId(), $token);
        $html = '<a target="_blank" href="'.$url.'">Email Label</a>';
        return $html;
    }

}