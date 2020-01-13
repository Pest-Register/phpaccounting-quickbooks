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
    public function getTaxRates(){
        $taxRates = [];
        if ($this->data instanceof IPPTaxCode){
            $taxRate = $this->data;
            $newTaxRate = [];
            $newTaxRate['accounting_id'] = $taxRate->Id;
            $newTaxRate['name'] = $taxRate->Name;
            $newTaxRate['description'] = $taxRate->Description;
            $newTaxRate['tax_type'] = $taxRate->Name;
            $newTaxRate['sync_token'] = $taxRate->SyncToken;
            $newTaxRate['quickbooks_tax_rate_id'] = $taxRate->SalesTaxRateList->TaxRateDetail->TaxRateRef;
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
                $newTaxRate['description'] = $taxRate->Description;
                $newTaxRate['name'] = $taxRate->Name;
                $newTaxRate['tax_type'] = $taxRate->Name;
                if ($taxRate->SalesTaxRateList) {
                    $newTaxRate['quickbooks_tax_rate_id'] = $taxRate->SalesTaxRateList->TaxRateDetail->TaxRateRef;
                } elseif ($taxRate->PurchaseTaxRateList) {
                    $newTaxRate['quickbooks_tax_rate_id'] = $taxRate->PurchaseTaxRateList->TaxRateDetail->TaxRateRef;
                }

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