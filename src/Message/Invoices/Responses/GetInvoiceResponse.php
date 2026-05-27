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
     * Tolerance for floating-point currency comparisons. The package is
     * standalone, so it can't read accounting.php — keep this in sync with
     * config('accounting.php_float_check') on the Mira side.
     */
    private const FLOAT_TOLERANCE = 0.001;

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
            if (is_object($data)) {
                // IPPReferenceType — `value` is the QB customer id, `name` is
                // the display name. Capturing both lets Mira show the
                // customer name on synced invoices without an extra Customer
                // lookup per invoice.
                $newContact['accounting_id'] = $data->value ?? null;
                $newContact['name'] = $data->name ?? null;
            } else {
                // Defensive: a raw scalar slipped through somewhere upstream.
                $newContact['accounting_id'] = $data;
            }
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
            $linkedTransactions = [];
            // LinkedTxn is either a single IPPLinkedTxn or an array of them.
            $iterable = $data instanceof IPPLinkedTxn ? [$data] : $data;
            foreach ($iterable as $transaction) {
                if (! $transaction) {
                    continue;
                }
                // Record every link type — CreditMemo, Payment, ReimburseCharge,
                // JournalEntry, BillPayment etc. — so Mira can reconcile balances
                // (e.g. an invoice partially settled by a credit memo would
                // otherwise look unpaid).
                $linkedTransactions[] = [
                    'accounting_id' => $transaction->TxnId,
                    'type' => $transaction->TxnType,
                ];
                if ($transaction->TxnType === 'Payment') {
                    $invoice['payments'][] = [
                        'accounting_id' => $transaction->TxnId,
                    ];
                }
            }
            $invoice['linked_transactions'] = $linkedTransactions;
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

        // Float-tolerant comparison — direct `==` on currency values has bitten
        // us when a $0.0001 rounding diff makes a fully-paid invoice show as
        // OPEN. Mira's accounting.php_float_check uses the same 0.001 default.
        $amountDue = (float) $newInvoice['amount_due'];
        $total = (float) $newInvoice['total'];
        if (abs($amountDue) < self::FLOAT_TOLERANCE) {
            $newInvoice['status'] = 'PAID';
        } elseif ($amountDue > self::FLOAT_TOLERANCE && abs($amountDue - $total) > self::FLOAT_TOLERANCE) {
            $newInvoice['status'] = 'PARTIAL';
        } else {
            $newInvoice['status'] = 'OPEN';
        }

        // QB's documented void behaviour: TotalAmt is zeroed and PrivateNote is
        // set to "Voided". Requiring both reduces false positives where a user
        // happens to type "Voided" in their private note text.
        if (abs($total) < self::FLOAT_TOLERANCE && $invoice->PrivateNote === 'Voided') {
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
