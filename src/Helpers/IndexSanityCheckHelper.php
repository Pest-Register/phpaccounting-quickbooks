<?php

namespace PHPAccounting\Quickbooks\Helpers;

/**
 * Class IndexSanityCheckHelper
 * @package PHPAccounting\Quickbooks\Helpers
 */
class IndexSanityCheckHelper
{
    public static function indexSanityCheck ($key, $data) {
        $value = '';
        if (is_object($data)){
            if (property_exists($data, $key)) {
                $value = $data->$key;
            }
        }
        elseif(is_array($data)) {
            if (array_key_exists($key, $data)) {
                $value = $data[$key];
            }
        }
        return $value;
    }
}