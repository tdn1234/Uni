<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Helper;

class Phone extends \Magento\Framework\App\Helper\AbstractHelper {
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct(
            $context
        );
    }


    /**
     * Filter all characters that don't belong in a phone number.
     * Will only keep +, 0-9
     *
     * @param string $value
     * @return string
     */
    public function filterPhoneNumber($value)
    {
        $value = preg_replace('/[^0-9\+]/si', '', $value);
        return $value;
    }

    /**
     * Check if phone number has correct format including country prefix
     *
     * @param $number
     * @param $countryCode
     * @return string
     */
    public function getCountryPhonePrefix($number, $countryCode)
    {
        $countryCodes = $this->_getCountryPhonePrefixArray();
        if (substr($number, 0, 1) == "+") {
            // We must assume that the number contans a country prefix
            return $number;
        } elseif(substr($number, 0, 2) == "00") {
            // We have to assume the number have country prefix but we have to replace the 00 with +
            $telephone = substr($number, 2);
            return "+" . $telephone;

        } elseif (substr($number, 0, 1) == "0") {
            // The number is missing country code, we need to add that
            $telephone = substr($number, 1);
            if (array_key_exists($countryCode, $countryCodes)) {
                $telephone = $countryCodes[$countryCode] . $telephone;
            } else {
                // The prefix is not available in the list.
                return $number;
            }
            return $telephone;
        } else {
            // Unknown case, try to match against its land code
            if (array_key_exists($countryCode, $countryCodes)) {
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

            // Return number as it was to avoid errors
            return $number;
        }

    }

    /**
     * Get all available country codes
     *
     * @return array
     */
    protected function _getCountryPhonePrefixArray()
    {
        return array(
            'AF' => '+93',
            'AL' => '+355',
            'DZ' => '+213',
            'AD' => '+376',
            'AO' => '+244',
            'AG' => '+1-268',
            'AR' => '+54',
            'AM' => '+374',
            'AU' => '+61',
            'AT' => '+43',
            'AZ' => '+994',
            'BS' => '+1-242',
            'BH' => '+973',
            'BD' => '+880',
            'BB' => '+1-246',
            'BY' => '+375',
            'BE' => '+32',
            'BZ' => '+501',
            'BJ' => '+229',
            'BT' => '+975',
            'BO' => '+591',
            'BA' => '+387',
            'BW' => '+267',
            'BR' => '+55',
            'BN' => '+673',
            'BG' => '+359',
            'BF' => '+226',
            'BI' => '+257',
            'KH' => '+855',
            'CM' => '+237',
            'CA' => '+1',
            'CV' => '+238',
            'CF' => '+236',
            'TD' => '+235',
            'CL' => '+56',
            'CN' => '+86',
            'CO' => '+57',
            'KM' => '+269',
            'CD' => '+243',
            'CG' => '+242',
            'CR' => '+506',
            'CI' => '+225',
            'HR' => '+385',
            'CU' => '+53',
            'CY' => '+357',
            'CZ' => '+420',
            'DK' => '+45',
            'DJ' => '+253',
            'DM' => '+1-767',
            'DO' => '+1-809',
            'EC' => '+593',
            'EG' => '+20',
            'SV' => '+503',
            'GQ' => '+240',
            'ER' => '+291',
            'EE' => '+372',
            'ET' => '+251',
            'FJ' => '+679',
            'FI' => '+358',
            'FR' => '+33',
            'GA' => '+241',
            'GM' => '+220',
            'GE' => '+995',
            'DE' => '+49',
            'GH' => '+233',
            'GR' => '+30',
            'GD' => '+1-473',
            'GT' => '+502',
            'GN' => '+224',
            'GW' => '+245',
            'GY' => '+592',
            'HT' => '+509',
            'HN' => '+504',
            'HU' => '+36',
            'IS' => '+354',
            'IN' => '+91',
            'ID' => '+62',
            'IR' => '+98',
            'IQ' => '+964',
            'IE' => '+353',
            'IL' => '+972',
            'IT' => '+39',
            'JM' => '+1-876',
            'JP' => '+81',
            'JO' => '+962',
            'KZ' => '+7',
            'KE' => '+254',
            'KI' => '+686',
            'KP' => '+850',
            'KR' => '+82',
            'KW' => '+965',
            'KG' => '+996',
            'LA' => '+856',
            'LV' => '+371',
            'LB' => '+961',
            'LS' => '+266',
            'LR' => '+231',
            'LY' => '+218',
            'LI' => '+423',
            'LT' => '+370',
            'LU' => '+352',
            'MK' => '+389',
            'MG' => '+261',
            'MW' => '+265',
            'MY' => '+60',
            'MV' => '+960',
            'ML' => '+223',
            'MT' => '+356',
            'MH' => '+692',
            'MR' => '+222',
            'MU' => '+230',
            'MX' => '+52',
            'FM' => '+691',
            'MD' => '+373',
            'MC' => '+377',
            'MN' => '+976',
            'ME' => '+382',
            'MA' => '+212',
            'MZ' => '+258',
            'MM' => '+95',
            'NA' => '+264',
            'NR' => '+674',
            'NP' => '+977',
            'NL' => '+31',
            'NZ' => '+64',
            'NI' => '+505',
            'NE' => '+227',
            'NG' => '+234',
            'NO' => '+47',
            'OM' => '+968',
            'PK' => '+92',
            'PW' => '+680',
            'PA' => '+507',
            'PG' => '+675',
            'PY' => '+595',
            'PE' => '+51',
            'PH' => '+63',
            'PL' => '+48',
            'PT' => '+351',
            'QA' => '+974',
            'RO' => '+40',
            'RU' => '+7',
            'RW' => '+250',
            'KN' => '+1-869',
            'LC' => '+1-758',
            'VC' => '+1-784',
            'WS' => '+685',
            'SM' => '+378',
            'ST' => '+239',
            'SA' => '+966',
            'SN' => '+221',
            'RS' => '+381',
            'SC' => '+248',
            'SL' => '+232',
            'SG' => '+65',
            'SK' => '+421',
            'SI' => '+386',
            'SB' => '+677',
            'SO' => '+252',
            'ZA' => '+27',
            'ES' => '+34',
            'LK' => '+94',
            'SD' => '+249',
            'SR' => '+597',
            'SZ' => '+268',
            'SE' => '+46',
            'CH' => '+41',
            'SY' => '+963',
            'TJ' => '+992',
            'TZ' => '+255',
            'TH' => '+66',
            'TL' => '+670',
            'TG' => '+228',
            'TO' => '+676',
            'TT' => '+1-868',
            'TN' => '+216',
            'TR' => '+90',
            'TM' => '+993',
            'TV' => '+688',
            'UG' => '+256',
            'UA' => '+380',
            'AE' => '+971',
            'GB' => '+44',
            'US' => '+1',
            'UY' => '+598',
            'UZ' => '+998',
            'VU' => '+678',
            'VA' => '+379',
            'VE' => '+58',
            'VN' => '+84',
            'YE' => '+967',
            'ZM' => '+260',
            'ZW' => '+263',
            'TW' => '+886',
            'CX' => '+61',
            'CC' => '+61',
            'HM' => '',
            'NF' => '+672',
            'NC' => '+687',
            'PF' => '+689',
            'YT' => '+262',
            'GP' => '+590',
            'PM' => '+508',
            'WF' => '+681',
            'TF' => '',
            'BV' => '',
            'CK' => '+682',
            'NU' => '+683',
            'TK' => '+690',
            'GG' => '+44',
            'IM' => '+44',
            'JE' => '+44',
            'AI' => '+1-264',
            'BM' => '+1-441',
            'IO' => '+246',
            'VG' => '+1-284',
            'KY' => '+1-345',
            'FK' => '+500',
            'GI' => '+350',
            'MS' => '+1-664',
            'PN' => '',
            'SH' => '+290',
            'GS' => '',
            'TC' => '+1-649',
            'MP' => '+1-670',
            'PR' => '+1-787',
            'AS' => '+1-684',
            'UM' => '',
            'GU' => '+1-671',
            'VI' => '+1-340',
            'HK' => '+852',
            'MO' => '+853',
            'FO' => '+298',
            'GL' => '+299',
            'GF' => '+594',
            'MQ' => '+596',
            'RE' => '+262',
            'AX' => '+358-18',
            'AW' => '+297',
            'AN' => '+599',
            'SJ' => '+47',
            'AC' => '+247',
            'TA' => '+290',
            'CS' => '+381',
            'PS' => '+970',
            'EH' => '+212',
        );
    }

}