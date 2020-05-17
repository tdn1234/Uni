<?php
namespace Pcxpress\Unifaun\Block\Adminhtml\Catalog\Product;


class PackageConfig extends \Magento\Framework\Data\Form\Element\Text
{

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory
     */
    protected $unifaunMysql4ShippingMethodCollectionFactory;

    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory,
        $data = []
    ) {
        $this->unifaunHelper = $unifaunHelper;
        $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
        parent::__construct(
            $factoryElement,
            $factoryCollection,
            $escaper,
            $data
        );
    }


    public function getElementHtml()
    {
        // Add shipping methods
        $shippingMethods = $this->_getShippingMethods();
        $html = '<script type="text/javascript"> var shippingMethods = new unifaunModule.setShippingMethods('. json_encode($shippingMethods) .'); </script>';

        $advices = $this->_getAdvices();
        $html .= '<script type="text/javascript"> var advices = new unifaunModule.setAdvices('. json_encode($advices) .'); </script>';

        $tableId = 'packageconfiguration-' . uniqid();
        $html .= '<div class="grid">
        <table class="data border" cellspacing="0" id="' . $tableId . '">
            <colgroup>
                <col></col>
                <col></col>
                <col></col>
                <col></col>
                <col width="30"></col>
            </colgroup>
            <thead>
                <tr class="headings">
                    <th>' . __('Width') . ' (' . $this->unifaunHelper->getStoreLengthUnit() . ')</th>
                    <th>' . __('Height') . ' (' . $this->unifaunHelper->getStoreLengthUnit() . ')</th>
                    <th>' . __('Depth') . ' (' . $this->unifaunHelper->getStoreLengthUnit() . ')</th>
                    <th>' . __('Weight') . ' (' . $this->unifaunHelper->getStoreWeightUnit() . ')</th>
                    <th style="width: 230px;">' . __('Goods type') . '</th>
                    <th>' . __('Shipping method') . '</th>
                    <th>' . __('Advice') . '</th>
                    <th class="last">&nbsp;</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="a-right">
                        <button class="scalable add" type="button"><span><span><span>' . __('Add Package') . '</span></span></span></button>
                    </td>
                </tr>
            </tfoot>
        </table>
        <script type="text/javascript">
            var x = function () {
                var packageConfig = new unifaunModule.packageConfig(\'' . $this->getName() . '\', \'' . $tableId . '\');';
        if (is_array($this->getValue())) {
            foreach ($this->getValue() as $packageConfiguration) { /* @var $packageConfiguration Pcxpress_Unifaun_Model_PackageConfiguration  */
                $html .='packageConfig.add({
                            width: ' . $this->getJson($packageConfiguration->getWidth()) . ',
                            height: ' . $this->getJson($packageConfiguration->getHeight()) . ',
                            depth: ' . $this->getJson($packageConfiguration->getDepth()) . ',
                            weight: ' . $this->getJson($packageConfiguration->getWeight()) . ',
                            goodsType: ' . $this->getJson($packageConfiguration->getGoodsType()) . ',
                            shippingMethod: ' . $this->getJson($packageConfiguration->getShippingMethod()) . ',
                            advice: ' . $this->getJson($packageConfiguration->getAdvice()) . '
                        });';
            }
        }

        $html .= '}();
            </script>
            </div>';



        return $html;
    }

    protected function _getShippingMethods()
    {
        $collection = $this->unifaunMysql4ShippingMethodCollectionFactory->create();
        $shippingMethods = array();
        foreach ($collection as $method) { /** @var $method Pcxpress_Unifaun_Model_ShippingMethod */
            $shippingMethods[$method->getShippingmethodId()] = $method->getTitle();
        }
        // Add the non chosen option
        $shippingMethods['none'] = __("None selected");
        return $shippingMethods;
    }

    protected function _getAdvices() {
        return array(
            'none'      => __("None"),
            'phone'     => __("Phone"),
            'postal'    => __("Postal"),
            'mobile'    => __("Cellphone"),
            'fax'       => __("Fax"),
            'email'     => __("E-mail")
        );
    }

    public function getJson($value)
    {
        return json_encode($value, JSON_HEX_QUOT);
    }

}