<?php

namespace Pcxpress\Unifaun\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Message\ManagerInterface;

class ErrorMessage extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    public function __construct(
        Context $context,
        ManagerInterface $messageManager
    )
    {
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    public function _logShipmentErrorsMessage(string $message, \Magento\Sales\Model\Order\Shipment $shipment = null)
    {
        if ($shipment instanceof \Magento\Sales\Model\Order\Shipment && $shipment->getIncrementId()) {
            $message = $shipment->getIncrementId() . ": " . $message;
        }
        $this->messageManager->addError($message);
    }
}