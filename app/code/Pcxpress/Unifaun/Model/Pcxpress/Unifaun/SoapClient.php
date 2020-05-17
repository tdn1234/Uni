<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Pcxpress\Unifaun;

class SoapClient extends \SoapClient
{
    protected $_missingClass = array();

    /**
     * @var \Pcxpress\Unifaun\Helper\Classmap
     */
    protected $unifaunClassmapHelper;

    /**
     * @var \Pcxpress\Unifaun\Helper\Data
     */
    protected $unifaunHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function  __construct($wsdl, $options,
        \Pcxpress\Unifaun\Helper\Classmap $unifaunClassmapHelper,
        \Pcxpress\Unifaun\Helper\Data $unifaunHelper,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->unifaunClassmapHelper = $unifaunClassmapHelper;
        $this->unifaunHelper = $unifaunHelper;
        $this->logger = $logger;
        parent::__construct(
            $wsdl,
            $options
        );


        $client = new \SoapClient($wsdl, $options);

        if (!isset($options['classmap'])) {
            $options['classmap'] = array();
        }

        $options['soap_version'] = SOAP_1_1;
        $options['classmap'] = $this->unifaunClassmapHelper->getClassMapArray();

        unset($client);

        parent::SoapClient($wsdl, $options);
    }

    public function __soapCall($function_name, $arguments, $options = null, $input_headers = null, &$output_headers = null)
    {
        foreach ($arguments as $argumentId=>$argument) {
            foreach ($argument as $key=>$value) {
                if (is_object($value) && method_exists($value, "toSoapRequest")) {
                    $value = $value->toSoapRequest();
                    $arguments[$argumentId]->$key = $value;
                }
            }
        }

        $result = parent::__soapCall($function_name, $arguments, $options, $input_headers, $output_headers);

        return $result;
    }

    public function  __doRequest($request, $location, $action, $version, $one_way = null)
    {
        $debug = $this->unifaunHelper->isDebug();

        // Handle problem with SoapClient not handling multiple elements with same name in namespace
        // @todo We are not supporting CDATA for now.
        $request = preg_replace_callback('/<(.+?)>{{{multiple-element:(.+?)}}}<\/\1>/si', array($this, 'fixMultipleElements'), $request);

        if ($debug) {
            $this->logger->log(null, $request);
        }

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }

    /**
     * Because of a problem in SOAPClient not being able to create multiple elements with the same name when using namespaces
     * we are forced to have a temporary value that will be replaced by this function with the actual tags.
     * @return string
     */
    public function fixMultipleElements($match)
    {
        if (count($match) < 3) {
            return null;
        }

        $tag = $match[1];
        $values = explode("||", $match[2]);

        $result = array();
        foreach ($values as $value) {
            $result[] = '<' . $tag . '>' . $value . '</' . $tag . '>';
        }
        return join('', $result);
    }

    public function  __call($function_name, $arguments)
    {    	
        return $this->__soapCall($function_name, $arguments);
    }
}