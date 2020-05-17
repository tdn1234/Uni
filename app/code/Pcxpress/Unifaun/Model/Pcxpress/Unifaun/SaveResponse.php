<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class SaveResponse extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
    
    /**
     * Get Role
     * @return string
     */
    public function getResult()
    {
        return (isset($this->_properties['result']))? $this->_properties['result'] : '';
    }

    /**
     * Set consignment result
     * @param \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentResult $value
     */
    public function setResult(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentResult $value)
    {
        $this->_properties['result'] = $value;
    }

}