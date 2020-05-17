<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Controller\Adminhtml\Unifaun;

class LabelQueueController extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $backendAuthSession;

    /**
     * @var \Pcxpress\Unifaun\Model\LabelQueueFactory
     */
    protected $unifaunLabelQueueFactory;

    /**
     * @var \Magento\Sales\Model\Order\ShipmentFactory
     */
    protected $salesOrderShipmentFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\PdfLabel\DefaultFactory
     */
    protected $unifaunPdfLabelDefaultFactory;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\PdfLabel\Factory
     */
    protected $unifaunPdfLabelFactory;

    public function __construct(
        \Magento\Backend\Model\Auth\Session $backendAuthSession,
        \Pcxpress\Unifaun\Model\LabelQueueFactory $unifaunLabelQueueFactory,
        \Magento\Sales\Model\Order\ShipmentFactory $salesOrderShipmentFactory,
        \Pcxpress\Unifaun\Model\PdfLabel\DefaultFactory $unifaunPdfLabelDefaultFactory,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Model\PdfLabel\Factory $unifaunPdfLabelFactory
    ) {
        $this->backendAuthSession = $backendAuthSession;
        $this->unifaunLabelQueueFactory = $unifaunLabelQueueFactory;
        $this->salesOrderShipmentFactory = $salesOrderShipmentFactory;
        $this->unifaunPdfLabelDefaultFactory = $unifaunPdfLabelDefaultFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunPdfLabelFactory = $unifaunPdfLabelFactory;
    }
    protected function _isAllowed()
    {
        return $this->backendAuthSession->isAllowed('sales/unifaun/unifaun_labelqueue');
    }

    protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('sales/unifaun/unifaun_labelqueue')
                ->_addBreadcrumb(__('Pcxpress Unifaun: Label Queue'), __('Item Manager'));

        return $this;
    }

    public function execute()
    {
        $this->_initAction()->renderLayout();
    }
    
    public function printAction()
    {
        // Print selected label
        $id = $this->getRequest()->getParam('id');
        
        $label = $this->unifaunLabelQueueFactory->create()->load($id);
        $shipment = $this->salesOrderShipmentFactory->create()->load($label->getShipmentId());
        /* @var $shipment Mage_Sales_Model_Order_Shipment */
        
        $pdf = $this->unifaunPdfLabelDefaultFactory->create();
        /* @var $pdf Pcxpress_Unifaun_Model_PdfLabel_Default */
        
        $date = date('Y-m-d H:s');
        $pdf->addShipment($shipment);
        $label->setPrintedAt($date);
        $label->setStatus(1);
        $label->save();
        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="Etiketter-' . date('Y-m-d-H:s') . '.pdf"');
        echo($pdf->render());
        
        die();
    }

    public function massPrintAction()
    {
        $ids = $this->getRequest()->getParam('label_ids');
        
        $this->_initAction()->renderLayout();
    }
    
    public function doMassPrintAction()
    {
        $labelType = $this->unifaunHelper->getLabelType();
        
        $pdf = $this->unifaunPdfLabelFactory->create();
        
        $n = $this->getRequest()->getParam("n");
        $pdf->setStartPosition($n);

        $ids = $this->getRequest()->getParam("label_ids");
        if (!is_array($ids)) {
            $ids = explode(",", $ids);
        }
 
        foreach ($ids as $id){
            $label = $this->unifaunLabelQueueFactory->create()->load($id);
            $shipment = $this->salesOrderShipmentFactory->create()->load($label->getShipmentId());
            $pdf->addShipment($shipment);
            $label->setStatus(1);
            $label->setPrintedAt(time());
            $label->save();
        }
        try {
            $content = $pdf->render();
        } catch(Exception $e) {
            die("An error occured (" . $e->getMessage() . ")");
        }
        
        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="Etiketter-' . date('Y-m-d-H:s') . '.pdf"');
        echo($content);  
        
        die();
    }
    
    public function editAction()
    {

    }

    public function newAction()
    {
       
    }

    public function deleteAction()
    {

    }


}