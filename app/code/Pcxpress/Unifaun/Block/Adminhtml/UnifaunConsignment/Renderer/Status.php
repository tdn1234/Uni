<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\UnifaunConsignment\Renderer;

class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    // public function render(Varien_Object $row)
    // {
    //     $html = "<i class='fa fa-question-circle'></i>";        
    //     if (!count($row->getAllTracks())) {
    //         $html = "<i class='fa fa-times-circle'></i>";
    //     }else if (count($row->getAllTracks())) {
    //         $html = "<i class='fa fa-check-circle'></i>";
    //     }
    //     return $html;
    // }


    /**
     * @var \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory
     */
    protected $unifaunPcxpressUnifaunFactory;

    // }
public function __construct(
        \Pcxpress\Unifaun\Model\Pcxpress\UnifaunFactory $unifaunPcxpressUnifaunFactory
    ) {
        $this->unifaunPcxpressUnifaunFactory = $unifaunPcxpressUnifaunFactory;
    }
        public function render(\Magento\Framework\DataObject $row)
    {
        return; //optimize page loading spead
        $tracks = $row->getAllTracks();
        $isBooked = false;
        if(count($tracks)){
            $unifaun = $this->unifaunPcxpressUnifaunFactory->create();            
            foreach ($tracks as $track) {
                $consignmentNo = $track->getNumber();
                $isConsignmentBooked = $unifaun->getConsignmentStatusIsBooked($consignmentNo);

                $isBooked = ($isConsignmentBooked)? $isConsignmentBooked : $isBooked;
            }
        }
        return ($isBooked)? '<b>booked</b>' : '<a href="'.$this->getUrl('*/*/book', array('order_id' => $row->getOrder()->getId())).'">unbooked</a>';
    }

}