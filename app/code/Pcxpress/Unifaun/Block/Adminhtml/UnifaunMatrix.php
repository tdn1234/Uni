<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml;

class UnifaunMatrix extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    public function __construct(
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper
    )
    {
        $this->unifaunHelper = $unifaunHelper;
        $this->_controller = 'adminhtml_unifaunMatrix';
        $this->_blockGroup = 'unifaun';
        $this->_headerText = __('Pcxpress Unifaun %s: Shipping Methods', $this->unifaunHelper->getVersionNumber());
        $this->_addButtonLabel = __('Create Method');

        parent::__construct();
    }
}