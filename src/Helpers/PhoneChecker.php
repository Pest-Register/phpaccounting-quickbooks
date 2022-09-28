<?php

namespace PHPAccounting\Quickbooks\Helpers;

/**
 * Class PhoneChecker
 * @package PHPAccounting\Quickbooks\Helpers
 */
class PhoneChecker
{
    public static function standardise ($phone) {
        $country_code = $phone['country_code'] ?? null;
        $area_code = $phone['area_code'] ?? null;
        $phone_number = $phone['phone_number'] ?? null;

        $str = '';
        if ($country_code) {
            $str .= '+'.$country_code.' ';
        }
        if ($area_code) {
            $str .= $area_code.' ';
        }
        if ($phone_number) {
            $str .= $phone_number;
        }
        return $str;
    }
}
