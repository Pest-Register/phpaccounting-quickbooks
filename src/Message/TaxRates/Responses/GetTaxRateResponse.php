<?php

namespace PHPAccounting\Quickbooks\Message\TaxRates\Responses;

use Omnipay\Common\Message\AbstractResponse;
use QuickBooksOnline\API\Data\IPPTaxCode;
use QuickBooksOnline\API\Data\IPPTaxRate;

/**
 * Get Tax Rate(s) Response
 * @package PHPAccounting\Quickbooks\Message\TaxRate\Responses
 */
class GetTaxRateResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if (array_key_exists('error', $this->data)) {
            if ($this->data['error']['status']){
                return false;
            }
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data['error']['status']){
            if (strpos($this->data['error']['detail'], 'Token expired') !== false) {
                return 'The access token has expired';
            } else {
                return $this->data['error']['detail'];
            }
        }

        return null;
    }


    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getTaxRates(){
        $taxRates = [];
        var_dump($this->data);
        if ($this->data instanceof IPPTaxCode){
            $taxRate = $this->data;
            $newTaxRate = [];
            $newTaxRate['accounting_id'] = $taxRate->Id;
            $newTaxRate['name'] = $taxRate->Description;
            $newTaxRate['tax_type'] = $taxRate->Name;
            $newTaxRate['sync_token'] = $taxRate->SyncToken;
            $newTaxRate['rate'] = $taxRate->RateValue;
            $newTaxRate['is_asset'] = true;
            $newTaxRate['is_equity'] = true;
            $newTaxRate['is_expense'] = true;
            $newTaxRate['is_liability'] = true;
            $newTaxRate['is_revenue'] = true;
            array_push($taxRates, $newTaxRate);

        } else {
            foreach ($this->data as $taxRate) {
                $newTaxRate = [];
                $newTaxRate['accounting_id'] = $taxRate->Id;
                $newTaxRate['name'] = $taxRate->Description;
                $newTaxRate['tax_type'] = $taxRate->Name;
                $newTaxRate['rate'] = $taxRate->RateValue;
                $newTaxRate['is_asset'] = true;
                $newTaxRate['is_equity'] = true;
                $newTaxRate['is_expense'] = true;
                $newTaxRate['is_liability'] = true;
                $newTaxRate['is_revenue'] = true;
                array_push($taxRates, $newTaxRate);
            }
        }

        return $taxRates;
    }
}