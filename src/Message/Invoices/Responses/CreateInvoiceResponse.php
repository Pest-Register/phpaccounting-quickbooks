<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Responses;

use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use QuickBooksOnline\API\Data\IPPInvoice;

/**
 * Create Invoice(s) Response
 * @package PHPAccounting\Quickbooks\Message\Invoices\Responses
 */
class CreateInvoiceResponse extends AbstractResponse
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
     * Add LineItems to Invoice
     * @param $data
     * @param $invoice
     * @return mixed
     */
    private function parseLineItems($data, $invoice) {
        if ($data) {
            $lineItems = [];
            foreach($data as $lineItem) {
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

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInvoices(){
        $invoices = [];
        if ($this->data instanceof IPPInvoice){
            $invoice = $this->data;
            $newInvoice = [];
            $newInvoice['accounting_id'] = $invoice->Id;
            $newInvoice['sub_total'] = $invoice->TotalAmt;
            $newInvoice['total_tax'] = $invoice->TxnTaxDetail->TotalTax;
            $newInvoice['total'] = $invoice->TotalAmt;
            $newInvoice['sync_token'] = $invoice->SyncToken;
            $newInvoice['currency'] = $invoice->CurrencyRef;
            $newInvoice['invoice_number'] = $invoice->DocNumber;
            $newInvoice['amount_due'] = $invoice->Balance;
            $newInvoice['amount_paid'] = (float) $invoice->TotalAmt -  (float) $invoice->Balance;
            $newInvoice['date'] = $invoice->TxnDate;
            $newInvoice['due_date'] = $invoice->DueDate;
            $newInvoice['gst_inclusive'] = $invoice->GlobalTaxCalculation;
            $newInvoice['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $invoice->MetaData->LastUpdatedTime)->toDateTimeString();
            $newInvoice = $this->parseContact($invoice->CustomerRef, $newInvoice);
            $newInvoice = $this->parseLineItems($invoice->Line, $newInvoice);

            if ($newInvoice['amount_paid'] === (float) $newInvoice['total']) {
                $newInvoice['status'] = 'PAID';
            } else {
                $newInvoice['status'] = 'SUBMITTED';
            }
            if ($newInvoice['amount_paid'] > 0 && $newInvoice['amount_paid'] !== (float) $newInvoice['total']) {
                $newInvoice['status'] = 'PARTIAL';
            }
            array_push($invoices, $newInvoice);

        } else {
            foreach ($this->data as $invoice) {
                $newInvoice = [];
                $newInvoice['accounting_id'] = $invoice->Id;
                $newInvoice['sub_total'] = $invoice->TotalAmt;
                $newInvoice['total_tax'] = $invoice->TxnTaxDetail->TotalTax;
                $newInvoice['total'] = $invoice->TotalAmt;
                $newInvoice['sync_token'] = $invoice->SyncToken;
                $newInvoice['currency'] = $invoice->CurrencyRef;
                $newInvoice['invoice_number'] = $invoice->DocNumber;
                $newInvoice['amount_due'] = $invoice->Balance;
                $newInvoice['amount_paid'] = (float) $invoice->TotalAmt -  (float) $invoice->Balance;
                $newInvoice['date'] = $invoice->TxnDate;
                $newInvoice['due_date'] = $invoice->DueDate;
                $newInvoice['gst_inclusive'] = $invoice->GlobalTaxCalculation;
                $newInvoice['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $invoice->MetaData->LastUpdatedTime)->toDateTimeString();
                $newInvoice = $this->parseContact($invoice->CustomerRef, $newInvoice);
                $newInvoice = $this->parseLineItems($invoice->Line, $newInvoice);
                if ($newInvoice['amount_paid'] === (float) $newInvoice['total']) {
                    $newInvoice['status'] = 'PAID';
                } else {
                    $newInvoice['status'] = 'SUBMITTED';
                }
                if ($newInvoice['amount_paid'] > 0 && $newInvoice['amount_paid'] !== (float) $newInvoice['total']) {
                    $newInvoice['status'] = 'PARTIAL';
                }
                array_push($invoices, $newInvoice);
            }
        }

        return $invoices;
    }
}