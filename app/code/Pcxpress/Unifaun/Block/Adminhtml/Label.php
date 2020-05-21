<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Block\Adminhtml;

class Label extends \Magento\Backend\Block\Widget\Container
{

    public function __construct(\Magento\Backend\Block\Widget\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

//    public function __construct()
//    {
//        var_dump('block askdfniasdfn');
//        $this->_controller = 'adminhtml_label';
//        $this->_blockGroup = 'unifaun';
//        $this->_headerText = Mage::helper('core')->__('Pcxpress Unifaun: Labels');
//        parent::__construct();
//        $this->_removeButton('add');
//    }
}