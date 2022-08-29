<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Responses;

use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use QuickBooksOnline\API\Data\IPPInvoice;
use QuickBooksOnline\API\Data\IPPLinkedTxn;

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
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    $detail = $this->data['error']['detail'] ?? [];
                    return ErrorResponseHelper::parseErrorResponse(
                        $detail['message'] ?: null,
                        $this->data['error']['status'],
                        $detail['error_code'] ?: null,
                        $detail['status_code'] ?: null,
                        $detail['detail'] ?: null,
                        'Invoice');
                }
            } elseif (array_key_exists('status', $this->data)) {
                $detail = $this->data['detail'] ?? [];
                return ErrorResponseHelper::parseErrorResponse(
                    $detail['message'] ?: null,
                    $this->data['status'],
                    $detail['error_code'] ?: null,
                    $detail['status_code'] ?: null,
                    $detail['detail'] ?: null,
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
        $subtotal = 0;
        if ($data) {
            $lineItems = [];
            foreach($data as $lineItem) {
                if ($lineItem->Id) {
                    $newLineItem = [];
                    $newLineItem['description'] = $lineItem->Description;
                    $newLineItem['accounting_id'] = $lineItem->Id;
                    $newLineItem['quantity'] = 1;
                    $newLineItem['unit_amount'] = 0;
                    $newLineItem['line_amount'] = 0;
                    $salesLineDetail = $lineItem->SalesItemLineDetail;
                    if ($salesLineDetail) {
                        if ($lineItem->SalesItemLineDetail->UnitPrice) {
                            $newLineItem['unit_amount'] = $lineItem->SalesItemLineDetail->UnitPrice / $lineItem->SalesItemLineDetail->Qty;
                            $newLineItem['line_amount'] = $lineItem->SalesItemLineDetail->UnitPrice;
                            $newLineItem['amount'] = $lineItem->SalesItemLineDetail->UnitPrice;
                        }
                        if ($lineItem->SalesItemLineDetail->TaxInclusiveAmt) {
                            $newLineItem['unit_amount'] = $lineItem->SalesItemLineDetail->TaxInclusiveAmt / $lineItem->SalesItemLineDetail->Qty;
                            $newLineItem['line_amount'] = $lineItem->SalesItemLineDetail->TaxInclusiveAmt;
                            $newLineItem['amount'] = $lineItem->SalesItemLineDetail->TaxInclusiveAmt;
                        }
                        if ($lineItem->SalesItemLineDetail->Qty) {
                            $newLineItem['quantity'] = $lineItem->SalesItemLineDetail->Qty;
                        } else {
                            $newLineItem['quantity'] = 1;
                        }
                        $newLineItem['service_date'] = $lineItem->SalesItemLineDetail->ServiceDate;
                        $newLineItem['discount_rate'] = $lineItem->SalesItemLineDetail->DiscountRate;
                        $newLineItem['account_id'] = $lineItem->SalesItemLineDetail->ItemAccountRef;
                        $newLineItem['item_id'] = $lineItem->SalesItemLineDetail->ItemRef;
                        $newLineItem['tax_type_id'] = $lineItem->SalesItemLineDetail->TaxCodeRef;
                    }
                    $subtotal += $newLineItem['line_amount'];
                    array_push($lineItems, $newLineItem);
                } elseif ($lineItem->DiscountLineDetail) {
                    if ($lineItem->DiscountLineDetail->PercentBased) {
                        $invoice['discount_rate'] = $lineItem->DiscountLineDetail->DiscountPercent;
                    }
                    $invoice['discount_amount'] = $lineItem->Amount;
                }
            }
            $invoice['subtotal'] = $subtotal;
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
     * Add Payments to Invoice
     *
     * @param $data
     * @param $invoice
     * @return mixed
     */
    private function parsePayments($data, $invoice) {
        if ($data) {
            if ($data instanceof IPPLinkedTxn) {
                if ($data->TxnType === 'Payment') {
                    $newPayment = [];
                    $newPayment['accounting_id'] = $data->TxnId;
                    array_push($invoice['payments'], $newPayment);
                }
            } else {
                foreach($data as $transaction) {
                    if ($transaction) {
                        if ($transaction->TxnType === 'Payment') {
                            $newPayment = [];
                            $newPayment['accounting_id'] = $transaction->TxnId;
                            array_push($invoice['payments'], $newPayment);
                        }
                    }
                }
            }
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
            $newInvoice['amount_paid'] = (float) $invoice->TotalAmt - (float) $invoice->Balance;
            $newInvoice['deposit_amount'] = $invoice->Deposit;
            $newInvoice['deposit_account'] = $invoice->DepositToAccountRef;
            $newInvoice['date'] = date('Y-m-d', strtotime($invoice->TxnDate));
            $newInvoice['due_date'] = date('Y-m-d', strtotime($invoice->DueDate));
            $newInvoice['sync_token'] = $invoice->SyncToken;
            $newInvoice['gst_inclusive'] = $this->parseTaxCalculation($invoice->GlobalTaxCalculation);
            if ($invoice->MetaData->LastUpdatedTime) {
                $updatedAt = Carbon::parse($invoice->MetaData->LastUpdatedTime);
                $updatedAt->setTimezone('UTC');
                $newInvoice['updated_at'] = $updatedAt->toDateTimeString();
            }
            $newInvoice['payments'] = [];
            $newInvoice = $this->parseContact($invoice->CustomerRef, $newInvoice);
            $newInvoice = $this->parseLineItems($invoice->Line, $newInvoice);
            $newInvoice = $this->parsePayments($invoice->LinkedTxn, $newInvoice);

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
                $newInvoice['status'] = 'OPEN';
            }

            if ($invoice->PrivateNote === 'Voided') {
                $newInvoice['status'] = 'DELETED';
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
                $newInvoice['amount_paid'] = (float) $invoice->TotalAmt - (float) $invoice->Balance;
                $newInvoice['deposit_amount'] = $invoice->Deposit;
                $newInvoice['deposit_account'] = $invoice->DepositToAccountRef;
                $newInvoice['date'] = date('Y-m-d', strtotime($invoice->TxnDate));
                $newInvoice['due_date'] = date('Y-m-d', strtotime($invoice->DueDate));
                $newInvoice['sync_token'] = $invoice->SyncToken;
                $newInvoice['gst_inclusive'] = $this->parseTaxCalculation($invoice->GlobalTaxCalculation);
                if ($invoice->MetaData->LastUpdatedTime) {
                    $updatedAt = Carbon::parse($invoice->MetaData->LastUpdatedTime);
                    $updatedAt->setTimezone('UTC');
                    $newInvoice['updated_at'] = $updatedAt->toDateTimeString();
                }
                $newInvoice['payments'] = [];
                $newInvoice = $this->parseContact($invoice->CustomerRef, $newInvoice);
                $newInvoice = $this->parseLineItems($invoice->Line, $newInvoice);
                $newInvoice = $this->parsePayments($invoice->LinkedTxn, $newInvoice);

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
                    $newInvoice['status'] = 'OPEN';
                }

                if ($invoice->PrivateNote === 'Voided') {
                    $newInvoice['status'] = 'DELETED';
                }

                array_push($invoices, $newInvoice);
            }
        }

        return $invoices;
    }
}
