<?php

namespace Pcxpress\Unifaun\Model\Observer;

use Magento\Framework\App\RequestInterface;
use Pcxpress\Unifaun\Helper\Data;
use Pcxpress\Unifaun\Model\ShippingmethodFactory;

class CreateConsignmentLabels implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /** @var Data $unifaunHelper */
    protected $unifaunHelper;

    /** @var ShippingmethodFactory $shippingmethodFactory */
    protected $shippingmethodFactory;

    public function __construct(
        RequestInterface $request,
        Data $unifaunHelper,
        ShippingmethodFactory $shippingmethodFactory
    )
    {
        $this->request = $request;
        $this->unifaunHelper = $unifaunHelper;
        $this->shippingmethodFactory = $shippingmethodFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $shipment = $observer->getShipment();
        $request = $this->request;
        $order = $shipment->getOrder();

        if (!$order->getId()) {
            throw new \Exception('The dose not exists.');
        }

        $carrierCode = $order->getShippingMethod();

        if (strpos($carrierCode, \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE) !== false) {
            $methodId = null;
            if ($this->unifaunHelper->isTemplateChangeEnabled()) {
                $methodId = $request->getParam("unifaun_method_id");
            }

            if (!$methodId) {
                $method = explode("_", $order->getShippingMethod());

                if (!array_key_exists(1, $method)) {
                    throw new \Exception('Not a valid method');
                }

                $methodId = (int)$method[1];
            }

            $method = $this->shippingmethodFactory->create()->load($methodId);

            if ($method->isObjectNew()) {
                throw new \Exception('Shipping Method no longer exists.');
            }

            if ($method->getLabelOnly()) {
                $label = $this->unifaunLabelFactory->create()->load($shipment->getId(), 'shipment_id');

                if ($label->getId()) {
                    return true;
                }

                $labelModel = $this->unifaunLabelFactory->create();

                $labelModel->setStatus(0)
                    ->setShipmentId($shipment->getId())
                    ->setCreatedAt(time())
                    ->save();
                return true;
            }
        }
        return true;
    }
}