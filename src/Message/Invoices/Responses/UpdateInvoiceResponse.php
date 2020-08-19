<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Responses;

use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use QuickBooksOnline\API\Data\IPPInvoice;

/**
 * Update Invoice(s) Response
 * @package PHPAccounting\Quickbooks\Message\Invoices\Responses
 */
class UpdateInvoiceResponse extends AbstractResponse
{

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
                        'Invoice');
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
                    'Invoice');
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
     * Add LineItems to Invoice
     * @param $data
     * @param $invoice
     * @return mixed
     */
    private function parseLineItems($data, $invoice) {
        if ($data) {
            $lineItems = [];
            foreach($data as $lineItem) {
                if ($lineItem->Id) {
                    $newLineItem = [];
                    $newLineItem['description'] = $lineItem->Description;
                    $newLineItem['line_amount'] = $lineItem->Amount;
                    $newLineItem['accounting_id'] = $lineItem->Id;
                    $newLineItem['amount'] = $lineItem->Amount;

                    $salesLineDetail = $lineItem->SalesItemLineDetail;
                    if ($salesLineDetail) {
                        $newLineItem['unit_amount'] = $lineItem->SalesItemLineDetail->UnitPrice;
                        $newLineItem['quantity'] = $lineItem->SalesItemLineDetail->Qty;
                        $newLineItem['discount_rate'] = $lineItem->SalesItemLineDetail->DiscountRate;
                        $newLineItem['account_id'] = $lineItem->SalesItemLineDetail->ItemAccountRef;
                        $newLineItem['item_id'] = $lineItem->SalesItemLineDetail->ItemRef;
                        $newLineItem['tax_amount'] = abs((float) $lineItem->Amount - (float) $lineItem->SalesItemLineDetail->TaxInclusiveAmt);
                        $newLineItem['tax_type'] = $lineItem->SalesItemLineDetail->TaxCodeRef;
                    }
                } else {
                    if ($lineItem->DiscountLineDetail) {
                        $invoice['discount_amount'] = $lineItem->Amount;
                    } elseif ($lineItem->DetailType == 'SubTotalLineDetail') {
                        $invoice['sub_total'] = $lineItem->Amount;
                    }
                }

                array_push($lineItems, $newLineItem);
            }

            $invoice['invoice_data'] = $lineItems;
        }

        return $invoice;
    }

    /**
     * Add Contact to Invoice
     *
     * @param $data
     * @param $invoice
     * @return mixed
     */
    private function parseContact($data, $invoice) {
        if ($data) {
            $newContact = [];
            $newContact['accounting_id'] = $data;
            $invoice['contact'] = $newContact;
        }

        return $invoice;
    }

    private function parseTaxCalculation($data)  {
        if ($data) {
            switch($data) {
                case 'TaxExcluded':
                    return 'EXCLUSIVE';
                case 'TaxInclusive':
                    return 'INCLUSIVE';
                case 'NotApplicable':
                    return 'NONE';
            }
        }
        return 'NONE';
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInvoices(){
        $invoices = [];
        if ($this->data instanceof IPPInvoice){
            $invoice = $this->data;
            $newInvoice = [];
            $newInvoice['address'] = [];
            $newInvoice['accounting_id'] = $invoice->Id;
            $newInvoice['total_tax'] = $invoice->TxnTaxDetail->TotalTax;
            $newInvoice['total'] = $invoice->TotalAmt;
            $newInvoice['sync_token'] = $invoice->SyncToken;
            $newInvoice['currency'] = $invoice->CurrencyRef;
            $newInvoice['invoice_number'] = $invoice->DocNumber;
            $newInvoice['amount_due'] = $invoice->Balance;
            $newInvoice['amount_paid'] = (float) $invoice->TotalAmt -  (float) $invoice->Balance;
            $newInvoice['deposit_amount'] = $invoice->Deposit;
            $newInvoice['deposit_account'] = $invoice->DepositToAccountRef;
            $newInvoice['date'] = $invoice->TxnDate;
            $newInvoice['due_date'] = $invoice->DueDate;
            $newInvoice['sync_token'] = $invoice->SyncToken;
            $newInvoice['gst_inclusive'] = $this->parseTaxCalculation($invoice->GlobalTaxCalculation);
            $newInvoice['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $invoice->MetaData->LastUpdatedTime)->toDateTimeString();
            $newInvoice = $this->parseContact($invoice->CustomerRef, $newInvoice);
            $newInvoice = $this->parseLineItems($invoice->Line, $newInvoice);
            if ($invoice->BillAddr) {
                $newInvoice['address'] = [
                    'address_type' =>  'BILLING',
                    'address_line_1' => $invoice->BillAddr->Line1,
                    'city' => $invoice->BillAddr->City,
                    'postal_code' => $invoice->BillAddr->PostalCode,
                    'country' => $invoice->BillAddr->Country
                ];
            }
            if ($newInvoice['amount_due'] == 0) {
                $newInvoice['status'] = 'PAID';
            } else if ($newInvoice['amount_due'] > 0 && $newInvoice['amount_due'] !== $newInvoice['total']) {
                $newInvoice['status'] = 'PARTIAL';
            } else {
                $newInvoice['status'] = 'SUBMITTED';
            }

            array_push($invoices, $newInvoice);

        } else {
            foreach ($this->data as $invoice) {
                $newInvoice = [];
                $newInvoice['address'] = [];
                $newInvoice['accounting_id'] = $invoice->Id;
                $newInvoice['total_tax'] = $invoice->TxnTaxDetail->TotalTax;
                $newInvoice['total'] = $invoice->TotalAmt;
                $newInvoice['sync_token'] = $invoice->SyncToken;
                $newInvoice['currency'] = $invoice->CurrencyRef;
                $newInvoice['invoice_number'] = $invoice->DocNumber;
                $newInvoice['amount_due'] = $invoice->Balance;
                $newInvoice['amount_paid'] = (float) $invoice->TotalAmt -  (float) $invoice->Balance;
                $newInvoice['deposit_amount'] = $invoice->Deposit;
                $newInvoice['deposit_account'] = $invoice->DepositToAccountRef;
                $newInvoice['date'] = $invoice->TxnDate;
                $newInvoice['due_date'] = $invoice->DueDate;
                $newInvoice['sync_token'] = $invoice->SyncToken;
                $newInvoice['gst_inclusive'] = $this->parseTaxCalculation($invoice->GlobalTaxCalculation);
                $newInvoice['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $invoice->MetaData->LastUpdatedTime)->toDateTimeString();
                $newInvoice = $this->parseContact($invoice->CustomerRef, $newInvoice);
                $newInvoice = $this->parseLineItems($invoice->Line, $newInvoice);
                if ($invoice->BillAddr) {
                    $newInvoice['address'] = [
                        'address_type' =>  'BILLING',
                        'address_line_1' => $invoice->BillAddr->Line1,
                        'city' => $invoice->BillAddr->City,
                        'postal_code' => $invoice->BillAddr->PostalCode,
                        'country' => $invoice->BillAddr->Country
                    ];
                }
                if ($newInvoice['amount_due'] == 0) {
                    $newInvoice['status'] = 'PAID';
                } else if ($newInvoice['amount_due'] > 0 && $newInvoice['amount_due'] !== $newInvoice['total']) {
                    $newInvoice['status'] = 'PARTIAL';
                } else {
                    $newInvoice['status'] = 'SUBMITTED';
                }
                array_push($invoices, $newInvoice);
            }
        }

        return $invoices;
    }
}