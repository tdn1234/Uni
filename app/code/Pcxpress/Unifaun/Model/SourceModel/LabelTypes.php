<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\SourceModel;

class LabelTypes
{

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    public function __construct(
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper
    ) {
        $this->unifaunHelper = $unifaunHelper;
    }
    public function toOptionArray()
    {
    	
    	return $this->unifaunHelper->getLabelTypeArray();    		
    }
}