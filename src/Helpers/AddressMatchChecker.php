<?php

namespace PHPAccounting\Quickbooks\Helpers;

/**
 * Class AddressMatchChecker
 * @package PHPAccounting\Quickbooks\Helpers
 */
class AddressMatchChecker
{
    public static function doesAddressMatch ($address1 = null, $address2 = null) {
        if (!$address1 || !$address2 || !is_array($address1) || !is_array(($address2))) {
            return false;
        }

        $line1 = ($address1['address_line_1'] ?? null) == ($address2['address_line_1'] ?? null);
        $city = ($address1['city'] ?? null) == ($address2['city'] ?? null);
        $country = ($address1['country'] ?? null) == ($address2['country'] ?? null);
        $state = ($address1['state'] ?? null) == ($address2['state'] ?? null);
        $postal_code = ($address1['postal_code'] ?? null) == ($address2['postal_code'] ?? null);

        return $line1 && $city && $country && $state && $postal_code;
    }

    public static function standardise ($address) {
        return [
            'Line1' => IndexSanityCheckHelper::indexSanityCheck('address_line_1', $address),
            'City' => IndexSanityCheckHelper::indexSanityCheck('city', $address),
            'Country' => IndexSanityCheckHelper::indexSanityCheck('country', $address),
            'CountrySubDivisionCode' => IndexSanityCheckHelper::indexSanityCheck('state', $address),
            'PostalCode' => IndexSanityCheckHelper::indexSanityCheck('postal_code', $address)
        ];
    }
}
