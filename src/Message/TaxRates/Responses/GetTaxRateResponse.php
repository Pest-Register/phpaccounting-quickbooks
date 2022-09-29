<?php

namespace PHPAccounting\Quickbooks\Message\TaxRates\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPTaxCode;
use QuickBooksOnline\API\Data\IPPTaxRate;

/**
 * Get Tax Rate(s) Response
 * @package PHPAccounting\Quickbooks\Message\TaxRate\Responses
 */
class GetTaxRateResponse extends AbstractQuickbooksResponse
{

    private function parseData($taxRate) {
        $newTaxRate = [];
        $newTaxRate['accounting_id'] = $taxRate->Id;
        $newTaxRate['name'] = $taxRate->Name;
        $newTaxRate['description'] = $taxRate->Description;
        $newTaxRate['tax_type_id'] = $taxRate->Name;
        if ($taxRate->SalesTaxRateList) {
            $newTaxRate['tax_rate_id'] = $taxRate->SalesTaxRateList->TaxRateDetail->TaxRateRef;
        } elseif ($taxRate->PurchaseTaxRateList) {
            $newTaxRate['tax_rate_id'] = $taxRate->PurchaseTaxRateList->TaxRateDetail->TaxRateRef;
        }
        $newTaxRate['sync_token'] = $taxRate->SyncToken;
        $newTaxRate['is_asset'] = true;
        $newTaxRate['is_equity'] = true;
        $newTaxRate['is_expense'] = true;
        $newTaxRate['is_liability'] = true;
        $newTaxRate['is_revenue'] = true;

        return $newTaxRate;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getTaxRates(){
        $taxRates = [];
        if ($this->data instanceof IPPTaxCode){
            $newTaxRate = $this->parseData($this->data);
            $taxRates[] = $newTaxRate;
        } else {
            foreach ($this->data as $taxRate) {
                $newTaxRate = $this->parseData($taxRate);
                $taxRates[] = $newTaxRate;
            }
        }

        return $taxRates;
    }
}
