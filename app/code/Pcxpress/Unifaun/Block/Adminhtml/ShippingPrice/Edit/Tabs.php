<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingPrice\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs {

    public function __construct()
    {
        parent::__construct();
        $this->setId('shippingprice_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Pcxpress Unifaun'));
    }

    public function getUrl($route = '', $params = array())
    {
        $request = $this->getRequest();
        $params['method'] = $request->getParam("method");
        return parent::getUrl($route, $params);
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => __('Shipping Prices'),
            'title' => __('Shipping Prices'),
            'content' => $this->getLayout()->createBlock('\Pcxpress\Unifaun\Block\Adminhtml\ShippingPrice\Edit\Tab\Form')->toHtml(),
        ));


        return parent::_beforeToHtml();
    }

}