<?php

namespace PHPAccounting\Quickbooks\Message\TaxRateValues\Responses;

use Omnipay\Common\Message\AbstractResponse;
use QuickBooksOnline\API\Data\IPPTaxCode;
use QuickBooksOnline\API\Data\IPPTaxRate;

/**
 * Get Tax Rate(s) Response
 * @package PHPAccounting\Quickbooks\Message\TaxRate\Responses
 */
class GetTaxRateValuesResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data) {
            if ($this->data['error']['status']){
                if (strpos($this->data['error']['message'], 'Token expired') !== false || strpos($this->data['error']['message'], 'AuthenticationFailed') !== false) {
                    return 'The access token has expired';
                } else {
                    return $this->data['error']['message'];
                }
            }
        } else {
            return 'NULL Returned from API';
        }

        return null;
    }


    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getTaxRateValues(){
        $taxRates = [];
        if ($this->data instanceof IPPTaxRate){
            $taxRate = $this->data;
            $newTaxRate = [];
            $newTaxRate['rate'] = $taxRate->RateValue;
            array_push($taxRates, $newTaxRate);

        } else {
            foreach ($this->data as $taxRate) {
                $newTaxRate = [];
                $newTaxRate['rate'] = $taxRate->RateValue;
                array_push($taxRates, $newTaxRate);
            }
        }

        return $taxRates;
    }
}