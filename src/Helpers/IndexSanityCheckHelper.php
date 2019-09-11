<?php

namespace PHPAccounting\Quickbooks\Helpers;

/**
 * Class IndexSanityCheckHelper
 * @package PHPAccounting\Quickbooks\Helpers
 */
class IndexSanityCheckHelper
{
    public static function indexSanityCheck ($key, $array) {
        $value = '';
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        return $value;
    }
}