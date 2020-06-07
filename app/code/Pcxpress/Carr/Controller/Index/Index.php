<?php

namespace Pcxpress\Carr\Controller\Index;

use Magento\Framework\App\Action\Context;
use Pcxpress\Unifaun\Model\Shippingrate;
use Pcxpress\Unifaun\Model\ShippingrateFactory;
class Index extends \Magento\Framework\App\Action\Action
{
    protected $shippingRate;
    public function __construct(Context $context, ShippingrateFactory $shippingrate)
    {
        $collection = $shippingrate->create()->getCollection();
        var_dump($collection->count());
        var_dump(get_class($shippingrate));die;
        parent::__construct($context);
    }

    public function execute()
    {
        die('asdf');
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}