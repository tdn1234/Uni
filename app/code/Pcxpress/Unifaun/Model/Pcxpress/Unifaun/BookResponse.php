<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class BookResponse extends \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract
{
    
    public function setResult(\Pcxpress\Unifaun\Model\Pcxpress\Unifaun\ConsignmentResult $value)
    {
        $this->_properties['result'] = $value;
    }

     public function getResult()
    {
        return (isset($this->_properties['result']))? $this->_properties['result'] : '';
    }

}