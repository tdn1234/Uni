<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml;

class UnificationMatrix extends \Magento\Backend\Block\Widget\Grid\Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_unificationMatrix';
        $this->_blockGroup = 'unifaun';
        $this->_headerText = Mage::helper('core')->__('Shipping Methods - Unification Matrix');
				$data = array(
					'label' 	=>  'Back',
					'onclick' => 'setLocation(\'' . $this->getUrl('unifaun/adminhtml_shippingMethod/*') . '\')',
					'class'   =>  'back'
   			);
				$this->addButton ('back', $data, 0, 100,  'header'); 
        parent::__construct();
				$this->_removeButton('add');
    }
}