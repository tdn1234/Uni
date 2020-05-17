<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Block\Adminhtml\Label;

class SelectStart
    //extends Mage_Adminhtml_Block_Page
{

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\PdfLabel\Factory
     */
    protected $unifaunPdfLabelFactory;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    public function __construct(
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Model\PdfLabel\Factory $unifaunPdfLabelFactory,
        \Magento\Framework\DataObjectFactory $dataObjectFactory
    ) {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunPdfLabelFactory = $unifaunPdfLabelFactory;
    }
    public function getOptions()
	{
		$helper = $this->unifaunHelper;
		$type = $helper->getLabelType();

		$class = $this->unifaunPdfLabelFactory->create();
		$reflect = new \ReflectionClass(get_class($class));
		$constants = $reflect->getConstants();
		$data = array();
		foreach ($constants as $key=>$value) {
			$name = strtolower($key);
			$data[$name] = $value;
		}
		
		$dataObject = $this->dataObjectFactory->create($data);
		return $dataObject;
	}
    
	public function getUrlParams($n)
	{
		$ids = $this->getRequest()->getParam('label_ids'); 
		$return = array("label_ids" => join(",", $ids), "n" => $n);
		return $return;
	}
    
}