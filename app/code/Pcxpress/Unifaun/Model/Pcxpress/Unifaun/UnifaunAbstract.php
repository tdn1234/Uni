<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class UnifaunAbstract
{
    protected $_properties = array(
        'id' => null,
        'name' => null,
        'address'=>null,
        'city'=>null,
        'postcode'=>null,
        'state'=>null,
        'countrycode'=>null,
        'posX'=>null,
        'posY'=>null,
        'posSystem'=>null,
        'result' => null,
        'amount' => null,
        'currency' => null,
        'paymentMethod' => null,
        'accountNo' => null,
        'reference' => null,
        'contactPerson' => null, 
        'phone' => null, 
        'mobile' => null,
        'fax' => null,
        'email' => null,
        'consignmentNo' => null,
        'consignmentId' => null,
        'orderNo' => null,
        'orderType' => null,
        'automaticBooking' => null,
        'templateName' => null,
        'TransportProduct' => null,
        'Target' => null,
        'Contents' => null,
        'ConsignmentReference' => array(),
        'GoodsItem' => array(),
        'Note' => array(),
        'GoodsInvoice' => array(),
        'Part' => array(),
        'consignments' => null,
        'errors' => null,
        'documents' => null,
        'receipt' => null,
        'statusCode' => null,
        'goodsValue' => null,
        'goodsValueCurrency' => 'SEK',
        'code' => null,
        'description' => null,
        'level'=>null,
        'result' => null,
        "noOfPackages" => null,
        "weight" => null,
        "volume" => null,
        "length" => null,
        "width" => null,
        "height" => null,
        "loadingMetres" => null,
        "palletSpace" => null,
        "packageType" => null,
        "tag" => null,
        "goodsType" => null,
        "goodsValue" => null,
        "goodsValueCurrency" => null,
        "netWeight" => null,
        "weightUnit" => null,
        "volumeUnit" => null,
        "lengthUnit" => null,
        "PackageIds" => null,
        "DangerousGoods" => null,
        'role' => null,
        'Address' => null,
        'Communication' => null,
        'Reference' => null,
        'date' => null,
        'earliest' => null,
        'latest' => null,
        'instruction' => null,
        'location' => null,
        'reference' => null,
        'result' => null,
        'errors' => null, 
        "statusCode" => null, 
        "status" => null,
        'mode' => null,
        'code' => null,
        'advice' => null,
        'co' => null,
        'Pickup' => null,
        'PaymentInstruction' => null,
        'CustomsClearance' => null
    );
    
    protected $_namespace = null;
    protected $_namespaceForProperties = array();
    protected $_undefinedValues = array();

    /**
     * Return object for SOAP Request
     * @return \stdClass
     */
    public function toSoapRequest()
    {        
        
        $obj = new \stdClass();

        // Populate SOAP Request object with data
        foreach ($this->_properties as $key=>$value) {
            // Prepare value by function
            $functionName = "_prepare" . ucwords($key);
            if (method_exists($this, $functionName)) {
                $value = $this->$functionName($value);
            }

            if (is_array($value)) {
                // An array
                $result = array();
                foreach ($value as $itemId => $item) {
                    if ($item instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract) {
                        $result[$itemId] = $item->toSoapRequest();
                    } else {
                        $result[$itemId] = $item;
                    }
                }

                if (sizeof($result) > 0) {
                    $namespace = $this->_namespace;
                    if (array_key_exists($key, $this->_namespaceForProperties)) {
                        $namespace = $this->_namespaceForProperties[$key];
                    }

                    if ($namespace !== null) {
                        $result = new \SoapVar($result, SOAP_ENC_OBJECT, null, $namespace, $key, $namespace);
                    }

                    $obj->$key = $result;
                }
            } elseif ($value instanceof \Pcxpress\Unifaun\Model\Pcxpress\Unifaun\UnifaunAbstract) {
                // An object was found in value
                $result = $value->toSoapRequest();
                if (sizeof($result) > 0) {
                    $obj->$key = $result;
                }
            } else {
                if ($value !== NULL) {
                    $namespace = $this->_namespace;
                    if (array_key_exists($key, $this->_namespaceForProperties)) {
                        $namespace = $this->_namespaceForProperties[$key];
                    }

                    if ($namespace !== null && !$value instanceof \stdClass) {
                        $value = new \SoapVar((string)$value, null, null, $namespace, $key, $namespace);
                    }
                    $obj->$key = $value;
                }
            }

        }

        return $obj;
    }

    public function __set($name, $value)
    {
        $functionName = "set" . ucwords($name);
        if (method_exists($this, $functionName)) {
            return $this->$functionName($value);
        } else {
            $this->_undefinedValues[$name] = $value;
        }
    }

    public function __get($name)
    {
        $functionName = "get" . ucwords($name);
        if (method_exists($this, $functionName)) {
            return $this->$functionName();
        }
    }

}