<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Responses;

use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use QuickBooksOnline\API\Data\IPPInvoice;

/**
 * Get Invoice(s) Response
 * @package PHPAccounting\Quickbooks\Message\Invoices\Responses
 */
class GetInvoiceResponse extends AbstractResponse
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
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    return ErrorResponseHelper::parseErrorResponse($this->data['error']['detail']['message'], 'Invoice');
                }
            } elseif (array_key_exists('status', $this->data)) {
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'], 'Invoice');
            }
        } else {
            return ['message' => 'NULL Returned from API or End of Pagination', 'exception' =>'NULL Returned from API or End of Pagination' ];
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

                    $newLineItem['quantity'] = 0;
                    $newLineItem['unit_amount'] = 0;
                    $salesLineDetail = $lineItem->SalesItemLineDetail;
                    if ($salesLineDetail) {
                        if ($lineItem->SalesItemLineDetail->UnitPrice) {
                            $newLineItem['unit_amount'] = $lineItem->SalesItemLineDetail->UnitPrice;
                        }
                        if ($lineItem->SalesItemLineDetail->Qty) {
                            $newLineItem['quantity'] = $lineItem->SalesItemLineDetail->Qty;
                        } else {
                            $newLineItem['quantity'] = 0;
                        }
                        $newLineItem['service_date'] = $lineItem->SalesItemLineDetail->ServiceDate;
                        $newLineItem['discount_rate'] = $lineItem->SalesItemLineDetail->DiscountRate;
                        $newLineItem['account_id'] = $lineItem->SalesItemLineDetail->ItemAccountRef;
                        $newLineItem['item_id'] = $lineItem->SalesItemLineDetail->ItemRef;
                        $newLineItem['tax_amount'] = abs((float) $lineItem->Amount - (float) $lineItem->SalesItemLineDetail->TaxInclusiveAmt);
                        $newLineItem['tax_type'] = $lineItem->SalesItemLineDetail->TaxCodeRef;
                    }
                    array_push($lineItems, $newLineItem);
                } elseif ($lineItem->DiscountLineDetail) {
                    $invoice['discount_amount'] = $lineItem->Amount;

                } elseif ($lineItem->DetailType == 'SubTotalLineDetail') {
                    $invoice['sub_total'] = $lineItem->Amount;
                }
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
            $newInvoice['currency'] = $invoice->CurrencyRef;
            $newInvoice['invoice_number'] = $invoice->DocNumber;
            $newInvoice['amount_due'] = $invoice->Balance;
            $newInvoice['amount_paid'] = (float) $invoice->TotalAmt -  (float) $invoice->Balance;
            $newInvoice['deposit_amount'] = $invoice->Deposit;
            $newInvoice['deposit_account'] = $invoice->DepositToAccountRef;
            $newInvoice['date'] = date('Y-m-d', strtotime($invoice->TxnDate));
            $newInvoice['due_date'] = date('Y-m-d', strtotime($invoice->DueDate));
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
                $newInvoice['currency'] = $invoice->CurrencyRef;
                $newInvoice['invoice_number'] = $invoice->DocNumber;
                $newInvoice['amount_due'] = $invoice->Balance;
                $newInvoice['amount_paid'] = (float) $invoice->TotalAmt -  (float) $invoice->Balance;
                $newInvoice['deposit_amount'] = $invoice->Deposit;
                $newInvoice['deposit_account'] = $invoice->DepositToAccountRef;
                $newInvoice['date'] = date('Y-m-d', strtotime($invoice->TxnDate));
                $newInvoice['due_date'] = date('Y-m-d', strtotime($invoice->DueDate));
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
                } else if ($newInvoice['amount_due'] > 0 && $newInvoice['amount_due'] != $newInvoice['total']) {
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