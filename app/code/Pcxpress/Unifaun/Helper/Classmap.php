<?php
namespace Pcxpress\Unifaun\Helper;

class Classmap extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct(
            $context
        );
    }

	public function getClassMapArray()
	{
		return array(
			'GoodsGroup' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_GoodsGroup',
            'GoodsGroupItem' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_GoodsGroupItem',
            'GoodsItem' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_GoodsItem',
            'GoodsInvoice' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_GoodsInvoice',
            'InvoiceNote' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_InvoiceNote',
            'PackageIds' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_PackageIds',
            'DangerousGoods' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_DangerousGoods',
            'ConsignmentList' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_ConsignmentList',
            'Communication' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Communication',
            'Consignment' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Consignment',
            'Part' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Part',
            'Address' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Address',
            
            'Reference' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Reference',
            'ConsignmentReference' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_ConsignmentReference',            
            'Category' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Category',
            'TransportProduct' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_TransportProduct',
            'Target' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Target',
            'Contents' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Contents',
            'ContentItem' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_ContentItem',
            
            'Note' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Note',
            'PaymentInstruction' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_PaymentInstruction',
            'AddService' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_AddService',
            'Tod' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Tod',
            'Cod' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Cod',
            'Pickup' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Pickup',
            'Delivery' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Delivery',
            'Transport' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Transport',
            'CustomsClearance' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_CustomsClearance',
            'Sortcode' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Sortcode',
            'Insurance' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Insurance',
            'email' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Email',
            'mobile' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Mobile',
            'Error' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Error',
            'ConsignmentResult' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_ConsignmentResult',
            'Receipt' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_Receipt',
            'bookResponse' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_BookResponse',
            'findByConsignmentNoResponse' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_FindByConsignmentNoResponse',
            'saveResponse' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_SaveResponse',
            'StatusResult' => 'Pcxpress_Unifaun_Model_Pcxpress_Unifaun_StatusResult',
        );
	}		
}