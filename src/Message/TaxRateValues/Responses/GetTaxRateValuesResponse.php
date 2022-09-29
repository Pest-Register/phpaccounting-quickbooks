<?php

namespace PHPAccounting\Quickbooks\Message\TaxRateValues\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPTaxCode;
use QuickBooksOnline\API\Data\IPPTaxRate;

/**
 * Get Tax Rate(s) Response
 * @package PHPAccounting\Quickbooks\Message\TaxRate\Responses
 */
class GetTaxRateValuesResponse extends AbstractQuickbooksResponse
{
    private function parseData($taxRate) {
        $newTaxRate = [];
        $newTaxRate['accounting_id'] = $taxRate->Id;
        $newTaxRate['rate'] = $taxRate->RateValue;

        return $newTaxRate;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getTaxRateValues(){
        $taxRates = [];
        if ($this->data instanceof IPPTaxRate){
            $newTaxRate = $this->parseData($this->data);
            $taxRates[] = $newTaxRate;
        } else {
            foreach ($this->data as $taxRate) {
                $newTaxRate = [];
                $newTaxRate['accounting_id'] = $taxRate->Id;
                $newTaxRate['rate'] = $taxRate->RateValue;
                $taxRates[] = $newTaxRate;
            }
        }

        return $taxRates;
    }
}
