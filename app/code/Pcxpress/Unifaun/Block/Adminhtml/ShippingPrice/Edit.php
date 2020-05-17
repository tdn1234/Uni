<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\ShippingPrice;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Framework\Registry $registry
    )
    {
        $this->registry = $registry;
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'unifaun';
        $this->_controller = 'adminhtml_shippingPrice';

        $this->_updateButton('save', 'label', __('Save Price'));
        $this->_updateButton('delete', 'label', __('Delete Price'));
    }


    public function getUrl($route = '', $params = array())
    {
        $request = $this->getRequest();
        $params['method'] = $request->getParam("method");
        return parent::getUrl($route, $params);
    }

    public function getHeaderText()
    {
        if( $this->registry->registry('shippingprice_data') && $this->registry->registry('shippingprice_data')->getId() ) {
            return __("Edit Price");
        } else {
            return __('Add Price');
        }
    }
}