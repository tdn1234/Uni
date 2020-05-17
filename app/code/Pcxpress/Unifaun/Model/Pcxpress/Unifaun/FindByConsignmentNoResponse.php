<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract;

class FindByConsignmentNoResponse extends UnifaunAbstract
{

    /**
     * Set consignment result
     * @param object $value
     */
    public function setResult($value)
    {
        $this->_properties['result'] = $value;
    }

    public function getResult()
    {
        return (isset($this->_properties['result'])) ? $this->_properties['result'] : '';
    }
}