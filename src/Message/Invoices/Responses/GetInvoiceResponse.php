<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Responses;

use Carbon\Carbon;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPInvoice;
use QuickBooksOnline\API\Data\IPPLinkedTxn;

/**
 * Get Invoice(s) Response
 * @package PHPAccounting\Quickbooks\Message\Invoices\Responses
 */
class GetInvoiceResponse extends AbstractQuickbooksResponse
{

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
                            $newLineItem['unit_amount'] = $lineItem->SalesItemLineDetail->UnitPrice;
                            $newLineItem['line_amount'] = $lineItem->SalesItemLineDetail->UnitPrice * $lineItem->SalesItemLineDetail->Qty;
                            $newLineItem['amount'] = $lineItem->SalesItemLineDetail->UnitPrice * $lineItem->SalesItemLineDetail->Qty;
                        }
                        if ($lineItem->SalesItemLineDetail->TaxInclusiveAmt) {
                            $newLineItem['unit_amount'] = $lineItem->SalesItemLineDetail->Qty > 0 ? ($lineItem->SalesItemLineDetail->TaxInclusiveAmt / $lineItem->SalesItemLineDetail->Qty) : 0;
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
     * @param $invoice
     * @return mixed
     */
    private function parseData($invoice) {
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
                'state' => $invoice->BillAddr->CountrySubDivisionCode,
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

        return $newInvoice;

    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInvoices(){
        $invoices = [];
        if ($this->data instanceof IPPInvoice){
            $newInvoice = $this->parseData($this->data);
            $invoices[] = $newInvoice;
        } else {
            foreach ($this->data as $invoice) {
                $newInvoice = $this->parseData($invoice);
                $invoices[] = $newInvoice;
            }
        }

        return $invoices;
    }
}
