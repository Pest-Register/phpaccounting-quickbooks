<?php

namespace PHPAccounting\Quickbooks\Message\TaxRates\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
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
            if (array_key_exists('status', $this->data)) {
                if (is_array($this->data)) {
                    if ($this->data['status'] == 'error') {
                        return false;
                    }
                } else {
                    if ($this->data->status == 'error') {
                        return false;
                    }
                }
            }
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
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            $errorCode = '';
            $statusCode = '';
            $detail = '';
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    if (array_key_exists('error_code', $this->data['error']['detail'])) {
                        $errorCode = $this->data['error']['detail']['error_code'];
                    }
                    if (array_key_exists('status_code', $this->data['error']['detail'])) {
                        $statusCode = $this->data['error']['detail']['status_code'];
                    }
                    if (array_key_exists('detail', $this->data['error']['detail'])){
                        $detail = $this->data['error']['detail']['detail'];
                    }
                    return ErrorResponseHelper::parseErrorResponse(
                        $this->data['error']['detail']['message'],
                        $this->data['error']['status'],
                        $errorCode,
                        $statusCode,
                        $detail,
                        'Tax Rate');
                }
            } elseif (array_key_exists('status', $this->data)) {
                if (array_key_exists('error_code', $this->data['detail'])) {
                    $errorCode = $this->data['detail']['error_code'];
                }
                if (array_key_exists('status_code', $this->data['detail'])) {
                    $statusCode = $this->data['detail']['status_code'];
                }
                if (array_key_exists('detail', $this->data['detail'])){
                    $detail = $this->data['detail']['detail'];
                }
                return ErrorResponseHelper::parseErrorResponse(
                    $this->data['detail']['message'],
                    $this->data['status'],
                    $errorCode,
                    $statusCode,
                    $detail,
                    'Tax Rate');
            }
        } else {
            return [
                'message' => 'NULL Returned from API or End of Pagination',
                'exception' =>'NULL Returned from API or End of Pagination',
                'error_code' => null,
                'status_code' => null,
                'detail' => null
            ];
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
            if ($taxRate->SalesTaxRateList) {
                $newTaxRate['quickbooks_tax_rate_id'] = $taxRate->SalesTaxRateList->TaxRateDetail->TaxRateRef;
            } elseif ($taxRate->PurchaseTaxRateList) {
                $newTaxRate['quickbooks_tax_rate_id'] = $taxRate->PurchaseTaxRateList->TaxRateDetail->TaxRateRef;
            }
            $newTaxRate['sync_token'] = $taxRate->SyncToken;
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