<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */

namespace Pcxpress\Unifaun\Helper;

use Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const WEBTA_SHIPPING_ID = 1;
    const PACTSOFT_SHIPPING_ID = 2;

    const SHIPPINGMETHOD_ID = 'shippingmethod_id';
    const UNIFAUN_CODE = "unifaun";
    protected $confPath = 'carriers/unifaun/';
    protected $defaultStoreId;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Eav\Model\EntityFactory
     */
    protected $eavEntityFactory;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory
     */
    protected $eavResourceModelEntityAttributeCollectionFactory;

    /**
     * @var \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory
     */
    protected $unifaunMysql4ShippingMethodCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $eavResourceModelEntityAttributeCollectionFactory,
        \Pcxpress\Unifaun\Model\Mysql4\ShippingMethod\CollectionFactory $unifaunMysql4ShippingMethodCollectionFactory
    )
    {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->eavEntityFactory = $eavEntityFactory;
        $this->eavResourceModelEntityAttributeCollectionFactory = $eavResourceModelEntityAttributeCollectionFactory;
        $this->unifaunMysql4ShippingMethodCollectionFactory = $unifaunMysql4ShippingMethodCollectionFactory;
        parent::__construct(
            $context
        );

        $defaultStoreId = $this->storeManager
            ->getWebsite(true)
            ->getDefaultGroup()
            ->getDefaultStoreId();
    }

    public function isMethodEnabled()
    {
        return $this->scopeConfig->getValue('carriers/' . \Pcxpress\Unifaun\Helper\Data::UNIFAUN_CODE . '/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getCalculationAttribute()
    {
        $field = "calculation_attribute";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    public function getStoreWeightUnit()
    {
        $field = "sectionheading_units/weight";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getStoreLengthUnit()
    {
        $field = "sectionheading_units/length";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getEnableCost()
    {
        $field = "sectionheading_units/length";
        $value = $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $value == 1 ? true : false;
    }

    public function getAdviceType()
    {
        $field = "sectionheading_admin/advice_type";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getAutoBooking()
    {
        $field = "sectionheading_admin/auto_booking";
        $value = $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $value == 1 ? true : false;
    }

    public function getPaymentMethodForCod()
    {
        $field = 'sectionheading_admin/cod_choices';
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getCodAccountNumber()
    {
        $field = "sectionheading_admin/cod_account_no";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getUseProforma()
    {
        $field = "sectionheading_admin/use_proforma";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    public function isDebug()
    {
        $field = "sectionheading_admin/use_proforma";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getUsername()
    {
        $field = "general/username";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getPassword()
    {
        $field = "general/password";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getGroupName()
    {
        $field = "general/groupname";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getPackageConfiguration($store)
    {
        $field = "sectionheading_attributes/package_config";
        $store = !$store ? $this->defaultStoreId : $store;
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }

    public function getDefaultAdviceType($store)
    {
        $field = "sectionheading_admin/advice_type";
        $store = !$store ? $this->defaultStoreId : $store;
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }

    public function isTemplateChangeEnabled()
    {
        $field = "sectionheading_attributes/enable_template_change";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function sanitizePhoneNumber($value)
    {
        $value = preg_replace('/[^0-9\+]/si', '', $value);
        return $value;
    }

    public function getCountryPhCode($number, $countryCode)
    {
        $countryCodes = $this->_getCountryPhCodeArray();
        if (substr($number, 0, 1) == "+") {
            return $number;
        } elseif (substr($number, 0, 2) == "00") {
            $telephone = substr($number, 2);
            return "+" . $telephone;
        } elseif (substr($number, 0, 1) == "0") {
            $telephone = substr($number, 1);
            if (array_key_exists($countryCode, $countryCodes)) {
                $telephone = $countryCodes[$countryCode] . $telephone;
            } else {
                return $number;
            }
            return $telephone;
        } else {
            if (isset($countryCodes[$countryCode])) {
                $countryPrefix = $countryCodes[$countryCode];
                $countryPrefix = substr($countryPrefix, 1);
                $countryPrefixLenght = strlen($countryPrefix);
                if (substr($number, 0, $countryPrefixLenght) == $countryPrefix) {
                    // Number contains landcode but not a +.
                    return "+" . $number;
                } else {
                    return "+" . $countryPrefix . $number;
                }
            }
            return $number;
        }
    }


    protected function _getCountryPhCodeArray()
    {
        $countryPhCode['AC'] = '+247';
        $countryPhCode['AD'] = '+376';
        $countryPhCode['AE'] = '+971';
        $countryPhCode['AF'] = '+93';
        $countryPhCode['AG'] = '+1-268';
        $countryPhCode['AI'] = '+1-264';
        $countryPhCode['AL'] = '+355';
        $countryPhCode['AM'] = '+374';
        $countryPhCode['AN'] = '+599';
        $countryPhCode['AO'] = '+244';
        $countryPhCode['AR'] = '+54';
        $countryPhCode['AS'] = '+1-684';
        $countryPhCode['AT'] = '+43';
        $countryPhCode['AU'] = '+61';
        $countryPhCode['AW'] = '+297';
        $countryPhCode['AX'] = '+358-18';
        $countryPhCode['AZ'] = '+994';
        $countryPhCode['BA'] = '+387';
        $countryPhCode['BB'] = '+1-246';
        $countryPhCode['BD'] = '+880';
        $countryPhCode['BE'] = '+32';
        $countryPhCode['BF'] = '+226';
        $countryPhCode['BG'] = '+359';
        $countryPhCode['BH'] = '+973';
        $countryPhCode['BI'] = '+257';
        $countryPhCode['BJ'] = '+229';
        $countryPhCode['BM'] = '+1-441';
        $countryPhCode['BN'] = '+673';
        $countryPhCode['BO'] = '+591';
        $countryPhCode['BR'] = '+55';
        $countryPhCode['BS'] = '+1-242';
        $countryPhCode['BT'] = '+975';
        $countryPhCode['BV'] = '';
        $countryPhCode['BW'] = '+267';
        $countryPhCode['BY'] = '+375';
        $countryPhCode['BZ'] = '+501';
        $countryPhCode['CA'] = '+1';
        $countryPhCode['CC'] = '+61';
        $countryPhCode['CD'] = '+243';
        $countryPhCode['CF'] = '+236';
        $countryPhCode['CG'] = '+242';
        $countryPhCode['CH'] = '+41';
        $countryPhCode['CI'] = '+225';
        $countryPhCode['CK'] = '+682';
        $countryPhCode['CL'] = '+56';
        $countryPhCode['CM'] = '+237';
        $countryPhCode['CN'] = '+86';
        $countryPhCode['CO'] = '+57';
        $countryPhCode['CR'] = '+506';
        $countryPhCode['CS'] = '+381';
        $countryPhCode['CU'] = '+53';
        $countryPhCode['CV'] = '+238';
        $countryPhCode['CX'] = '+61';
        $countryPhCode['CY'] = '+357';
        $countryPhCode['CZ'] = '+420';
        $countryPhCode['DE'] = '+49';
        $countryPhCode['DJ'] = '+253';
        $countryPhCode['DK'] = '+45';
        $countryPhCode['DM'] = '+1-767';
        $countryPhCode['DO'] = '+1-809';
        $countryPhCode['DZ'] = '+213';
        $countryPhCode['EC'] = '+593';
        $countryPhCode['EE'] = '+372';
        $countryPhCode['EG'] = '+20';
        $countryPhCode['EH'] = '+212';
        $countryPhCode['ER'] = '+291';
        $countryPhCode['ES'] = '+34';
        $countryPhCode['ET'] = '+251';
        $countryPhCode['FI'] = '+358';
        $countryPhCode['FJ'] = '+679';
        $countryPhCode['FK'] = '+500';
        $countryPhCode['FM'] = '+691';
        $countryPhCode['FO'] = '+298';
        $countryPhCode['FR'] = '+33';
        $countryPhCode['GA'] = '+241';
        $countryPhCode['GB'] = '+44';
        $countryPhCode['GD'] = '+1-473';
        $countryPhCode['GE'] = '+995';
        $countryPhCode['GF'] = '+594';
        $countryPhCode['GG'] = '+44';
        $countryPhCode['GH'] = '+233';
        $countryPhCode['GI'] = '+350';
        $countryPhCode['GL'] = '+299';
        $countryPhCode['GM'] = '+220';
        $countryPhCode['GN'] = '+224';
        $countryPhCode['GP'] = '+590';
        $countryPhCode['GQ'] = '+240';
        $countryPhCode['GR'] = '+30';
        $countryPhCode['GS'] = '';
        $countryPhCode['GT'] = '+502';
        $countryPhCode['GU'] = '+1-671';
        $countryPhCode['GW'] = '+245';
        $countryPhCode['GY'] = '+592';
        $countryPhCode['HK'] = '+852';
        $countryPhCode['HM'] = '';
        $countryPhCode['HN'] = '+504';
        $countryPhCode['HR'] = '+385';
        $countryPhCode['HT'] = '+509';
        $countryPhCode['HU'] = '+36';
        $countryPhCode['ID'] = '+62';
        $countryPhCode['IE'] = '+353';
        $countryPhCode['IL'] = '+972';
        $countryPhCode['IM'] = '+44';
        $countryPhCode['IN'] = '+91';
        $countryPhCode['IO'] = '+246';
        $countryPhCode['IQ'] = '+964';
        $countryPhCode['IR'] = '+98';
        $countryPhCode['IS'] = '+354';
        $countryPhCode['IT'] = '+39';
        $countryPhCode['JE'] = '+44';
        $countryPhCode['JM'] = '+1-876';
        $countryPhCode['JO'] = '+962';
        $countryPhCode['JP'] = '+81';
        $countryPhCode['KE'] = '+254';
        $countryPhCode['KG'] = '+996';
        $countryPhCode['KH'] = '+855';
        $countryPhCode['KI'] = '+686';
        $countryPhCode['KM'] = '+269';
        $countryPhCode['KN'] = '+1-869';
        $countryPhCode['KP'] = '+850';
        $countryPhCode['KR'] = '+82';
        $countryPhCode['KW'] = '+965';
        $countryPhCode['KY'] = '+1-345';
        $countryPhCode['KZ'] = '+7';
        $countryPhCode['LA'] = '+856';
        $countryPhCode['LB'] = '+961';
        $countryPhCode['LC'] = '+1-758';
        $countryPhCode['LI'] = '+423';
        $countryPhCode['LK'] = '+94';
        $countryPhCode['LR'] = '+231';
        $countryPhCode['LS'] = '+266';
        $countryPhCode['LT'] = '+370';
        $countryPhCode['LU'] = '+352';
        $countryPhCode['LV'] = '+371';
        $countryPhCode['LY'] = '+218';
        $countryPhCode['MA'] = '+212';
        $countryPhCode['MC'] = '+377';
        $countryPhCode['MD'] = '+373';
        $countryPhCode['ME'] = '+382';
        $countryPhCode['MG'] = '+261';
        $countryPhCode['MH'] = '+692';
        $countryPhCode['MK'] = '+389';
        $countryPhCode['ML'] = '+223';
        $countryPhCode['MM'] = '+95';
        $countryPhCode['MN'] = '+976';
        $countryPhCode['MO'] = '+853';
        $countryPhCode['MP'] = '+1-670';
        $countryPhCode['MQ'] = '+596';
        $countryPhCode['MR'] = '+222';
        $countryPhCode['MS'] = '+1-664';
        $countryPhCode['MT'] = '+356';
        $countryPhCode['MU'] = '+230';
        $countryPhCode['MV'] = '+960';
        $countryPhCode['MW'] = '+265';
        $countryPhCode['MX'] = '+52';
        $countryPhCode['MY'] = '+60';
        $countryPhCode['MZ'] = '+258';
        $countryPhCode['NA'] = '+264';
        $countryPhCode['NC'] = '+687';
        $countryPhCode['NE'] = '+227';
        $countryPhCode['NF'] = '+672';
        $countryPhCode['NG'] = '+234';
        $countryPhCode['NI'] = '+505';
        $countryPhCode['NL'] = '+31';
        $countryPhCode['NO'] = '+47';
        $countryPhCode['NP'] = '+977';
        $countryPhCode['NR'] = '+674';
        $countryPhCode['NU'] = '+683';
        $countryPhCode['NZ'] = '+64';
        $countryPhCode['OM'] = '+968';
        $countryPhCode['PA'] = '+507';
        $countryPhCode['PE'] = '+51';
        $countryPhCode['PF'] = '+689';
        $countryPhCode['PG'] = '+675';
        $countryPhCode['PH'] = '+63';
        $countryPhCode['PK'] = '+92';
        $countryPhCode['PL'] = '+48';
        $countryPhCode['PM'] = '+508';
        $countryPhCode['PN'] = '';
        $countryPhCode['PR'] = '+1-787';
        $countryPhCode['PS'] = '+970';
        $countryPhCode['PT'] = '+351';
        $countryPhCode['PW'] = '+680';
        $countryPhCode['PY'] = '+595';
        $countryPhCode['QA'] = '+974';
        $countryPhCode['RE'] = '+262';
        $countryPhCode['RO'] = '+40';
        $countryPhCode['RS'] = '+381';
        $countryPhCode['RU'] = '+7';
        $countryPhCode['RW'] = '+250';
        $countryPhCode['SA'] = '+966';
        $countryPhCode['SB'] = '+677';
        $countryPhCode['SC'] = '+248';
        $countryPhCode['SD'] = '+249';
        $countryPhCode['SE'] = '+46';
        $countryPhCode['SG'] = '+65';
        $countryPhCode['SH'] = '+290';
        $countryPhCode['SI'] = '+386';
        $countryPhCode['SJ'] = '+47';
        $countryPhCode['SK'] = '+421';
        $countryPhCode['SL'] = '+232';
        $countryPhCode['SM'] = '+378';
        $countryPhCode['SN'] = '+221';
        $countryPhCode['SO'] = '+252';
        $countryPhCode['SR'] = '+597';
        $countryPhCode['ST'] = '+239';
        $countryPhCode['SV'] = '+503';
        $countryPhCode['SY'] = '+963';
        $countryPhCode['SZ'] = '+268';
        $countryPhCode['TA'] = '+290';
        $countryPhCode['TC'] = '+1-649';
        $countryPhCode['TD'] = '+235';
        $countryPhCode['TF'] = '';
        $countryPhCode['TG'] = '+228';
        $countryPhCode['TH'] = '+66';
        $countryPhCode['TJ'] = '+992';
        $countryPhCode['TK'] = '+690';
        $countryPhCode['TL'] = '+670';
        $countryPhCode['TM'] = '+993';
        $countryPhCode['TN'] = '+216';
        $countryPhCode['TO'] = '+676';
        $countryPhCode['TR'] = '+90';
        $countryPhCode['TT'] = '+1-868';
        $countryPhCode['TV'] = '+688';
        $countryPhCode['TW'] = '+886';
        $countryPhCode['TZ'] = '+255';
        $countryPhCode['UA'] = '+380';
        $countryPhCode['UG'] = '+256';
        $countryPhCode['UM'] = '';
        $countryPhCode['US'] = '+1';
        $countryPhCode['UY'] = '+598';
        $countryPhCode['UZ'] = '+998';
        $countryPhCode['VA'] = '+379';
        $countryPhCode['VC'] = '+1-784';
        $countryPhCode['VE'] = '+58';
        $countryPhCode['VG'] = '+1-284';
        $countryPhCode['VI'] = '+1-340';
        $countryPhCode['VN'] = '+84';
        $countryPhCode['VU'] = '+678';
        $countryPhCode['WF'] = '+681';
        $countryPhCode['WS'] = '+685';
        $countryPhCode['YE'] = '+967';
        $countryPhCode['YT'] = '+262';
        $countryPhCode['ZA'] = '+27';
        $countryPhCode['ZM'] = '+260';
        $countryPhCode['ZW'] = '+263';

        return $countryPhCode;
    }

    public function convertWeightFromStoreToUnifaun($weight)
    {
        $storeUnit = $this->getStoreWeightUnit();
        $unifaunUnit = $this->getUnifaunWeightUnit();

        if ($storeUnit == $unifaunUnit) {
            return $weight;
        }

        if ($storeUnit == "g" && $unifaunUnit == "kg") {
            return floatval($weight) / 1000;
        }

        throw new \Exception("Unable to convert selected units (from " . $storeUnit . " to " . $unifaunUnit . ")");
    }

    public function getUnifaunWeightUnit()
    {
        return "kg";
    }

    public function getUnifaunLengthUnit()
    {
        return "cm";
    }

    public function getLabelType()
    {
        $field = "sectionheading_admin/label_type";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getSendGoodsValue()
    {
        $field = "sectionheading_admin/send_goods_value";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function convertLengthFromStoreToUnifaun($length)
    {
        $storeUnit = $this->getStoreLengthUnit();
        $unifaunUnit = $this->getUnifaunLengthUnit();

        if ($storeUnit == $unifaunUnit) {
            return $length;
        }

        if ($storeUnit == "cm" && $unifaunUnit == "m") {
            return floatval($length) / 1000;
        } elseif ($storeUnit == "m" && $unifaunUnit == "cm") {
            return floatval($length) / 1000;
        }

        throw new \Exception("Unable to convert selected units (from " . $storeUnit . " to " . $unifaunUnit . ")");
    }

    public function getDefaultGoodsType()
    {
        $field = "sectionheading_attributes/default_goods_type";
        return $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getCalculateZeroWeight()
    {
        $field = "sectionheading_admin/calculate_zero_weight";
        $value = $this->scopeConfig->getValue($this->confPath . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $value == 1 ? true : false;
    }

    public function getAdviceTypeArray()
    {
        $advices = array();
        $advices[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::NOADVICE, "label" => __("None"));
        $advices[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::PHONE, "label" => __("Phone"));
        $advices[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::POSTAL, "label" => __("Postal"));
        $advices[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::MOBILE, "label" => __("Cellphone"));
        $advices[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::FAX, "label" => __("Fax"));
        $advices[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Communication::EMAIL, "label" => __("E-mail"));

        return $advices;
    }

    public function getCalculationAttributeArray()
    {
        $calculationAttributes = array();
        $calculationAttributes[] = array("value" => "max_weight", "label" => __("Vikt"));
        $calculationAttributes[] = array("value" => "max_width", "label" => __("Bredd"));
        return $calculationAttributes;
    }

    public function getCalculationMethodsArray()
    {
        $calculationMethods = array();

        $calculationMethods[] = array("value" => "weight", "label" => __("Product Weight"));

        $calculationMethods[] = array("value" => "unit", "label" => __("Product Unit"));

        return $calculationMethods;
    }

    public function getLabelTypeArray()
    {
        $labelType = array();
        $labelType[] = array("value" => "default", "label" => __("Default"));

        return $labelType;
    }

    public function getLengthArray()
    {
        $lengths = array();
        $lengths[] = array(
            "value" => "cm",
            "label" => __("Centimeters"));
        return $lengths;
    }

    public function getPaymentMethods()
    {
        $methods = array();
        $methods[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod::PAYMENTMETHOD_CASH, "label" => "Cash");
        $methods[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod::PAYMENTMETHOD_CHEQUE, "label" => "Cheque");
        $methods[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod::PAYMENTMETHOD_POST, "label" => "Post");
        $methods[] = array("value" => \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\Cod::PAYMENTMETHOD_BANK, "label" => "Bank");

        return $methods;
    }

    public function getProductShippingArray()
    {
        $entityTypeId = $this->eavEntityFactory->create()->setType('catalog_product')->getTypeId();
        $attributes = $this->eavResourceModelEntityAttributeCollectionFactory->create()->setEntityTypeFilter($entityTypeId);

        $shippings = array();
        $shippings[] = array(
            "value" => "",
            "label" => __("Not used")
        );
        foreach ($attributes as $attribute) {
            $label = __($attribute->getFrontendLabel());
            if (!$label) {
                $label = $attribute->getAttributeCode();
            }
            $shippings[] = array(
                "value" => $attribute->getAttributeCode(),
                "label" => $label);
        }
        return $shippings;
    }

    public function getShippingGroupSortingArray()
    {
        $sorts = array();
        $sorts[] = array("value" => 'asc',
            "label" => __("Ascending"));
        $sorts[] = array("value" => 'desc',
            "label" => __("Descending"));

        return $sorts;
    }

    public function getShippingMethods()
    {
//        var_dump(get_class($this->unifaunMysql4ShippingMethodCollectionFactory));
////        var_dump(get_class($this->unifaunMysql4ShippingMethodCollectionFactory->create()));
//        var_dump(get_class_methods($this->unifaunMysql4ShippingMethodCollectionFactory));die;
//        $methods = array();
//        $methods[] = array(
//            'value' => 0,
//            'label' => 'None selected'
//        );
//        foreach ($this->unifaunMysql4ShippingMethodCollectionFactory->create() as $method) {
//            if (!$method->getActivated()) {
//                continue;
//            }
//            $methods[] = array(
//                'value' => $method->getId(),
//                'label' => $method->getTitle()
//            );
//        }

//        var_dump($methods);die;

        return array(0 => 'None selected');

//        var_dump($methods);die;
        return $methods;
    }

    public function getHiddenShippingMethods()
    {
        $methods = array();

        foreach ($this->unifaunMysql4ShippingMethodCollectionFactory->create() as $method) {
            if ($method->getFrontendVisibility()) {
                continue;
            }
            $methods[] = $method->getId();

        }

        return $methods;
    }

    public function getWeightArray()
    {

        $weights = array();
        $weights[] = array(
            "value" => "kg",
            "label" => __("Kilograms (kg)"));
        $weights[] = array(
            "value" => "g",
            "label" => __("Grams (g)"));
        return $weights;

    }

    /**
     * @return array
     */
    public function getCodPaymentMethods()
    {
        $options = array(
            array(
                'title' => __("Cash"),
                'value' => Cod::PAYMENTMETHOD_CASH,
                'selected' => $this->getPaymentMethodForCod() == Cod::PAYMENTMETHOD_CASH
            ),
            array(
                'title' => __("Cheque"),
                'value' => Cod::PAYMENTMETHOD_CHEQUE,
                'selected' => $this->getPaymentMethodForCod() == Cod::PAYMENTMETHOD_CHEQUE
            ),
            array(
                'title' => __("Post"),
                'value' => Cod::PAYMENTMETHOD_POST,
                'selected' => $this->getPaymentMethodForCod() == Cod::PAYMENTMETHOD_POST
            ),
            array(
                'title' => __("Bank"),
                'value' => Cod::PAYMENTMETHOD_BANK,
                'selected' => $this->getPaymentMethodForCod() == Cod::PAYMENTMETHOD_BANK
            )
        );

        return $options;
    }
}