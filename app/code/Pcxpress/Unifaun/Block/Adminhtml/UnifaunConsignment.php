<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml;

class UnifaunConsignment extends \Magento\Backend\Block\Widget\Grid\Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_unifaunConsignment';
        $this->_blockGroup = 'unifaun';
        $this->_headerText = __('Pcxpress Unifaun: Consignments');
        parent::__construct();
        $this->_removeButton('add');
    }
}