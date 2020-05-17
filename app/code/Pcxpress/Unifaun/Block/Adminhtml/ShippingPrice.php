<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml;

class ShippingPrice extends \Magento\Backend\Block\Widget\Grid\Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_shippingPrice';
        $this->_blockGroup = 'unifaun';
        $this->_headerText = __('Pcxpress Unifaun: Shipping Prices');
        $this->_addButtonLabel = __('Create Price');
        $this->_addBackButton();
        parent::__construct();
    }

    public function getUrl($route = '', $params = array())
    {
        $request = $this->getRequest();
        $params['method'] = $request->getParam("method");
        return parent::getUrl($route, $params);
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/unifaun_shippingMethod/');
    }

}