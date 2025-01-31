<?php


namespace PHPAccounting\Quickbooks\Message\Quotations\Responses;

use Carbon\Carbon;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPEstimate;

class CreateQuotationResponse extends AbstractQuickbooksResponse
{

    /**
     * Add LineItems to Quote
     * @param $data
     * @param $quote
     * @return mixed
     */
    private function parseLineItems($data, $quote) {
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
                        $quote['discount_rate'] = $lineItem->DiscountLineDetail->DiscountPercent;
                    }
                    $quote['discount_amount'] = $lineItem->Amount;
                }
            }
            $quote['subtotal'] = $subtotal;
            $quote['quotation_data'] = $lineItems;
        }
        return $quote;
    }

    /**
     * Add Contact to Quote
     *
     * @param $data
     * @param $quote
     * @return mixed
     */
    private function parseContact($data, $quote) {
        if ($data) {
            $newContact = [];
            $newContact['accounting_id'] = $data;
            $quote['contact'] = $newContact;
        }

        return $quote;
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

    private function parseStatus($data)  {
        if ($data) {
            switch($data) {
                case 'Accepted':
                case 'Converted':
                    return 'ACCEPTED';
                case 'Rejected':
                    return 'REJECTED';
                case 'Pending':
                    return 'SENT';
                case 'Closed':
                    return 'CLOSED';
            }
        }
        return 'DRAFT';
    }

    /**
     * @param $quote
     * @return mixed
     */
    private function parseData($quote) {
        $newQuote = [];
        $newQuote['address'] = [];
        $newQuote['accounting_id'] = $quote->Id;
        $newQuote['status'] = $this->parseStatus($quote->TxnStatus);
        $newQuote['total_tax'] = $quote->TxnTaxDetail->TotalTax;
        $newQuote['total'] = $quote->TotalAmt;
        $newQuote['currency'] = $quote->CurrencyRef;
        $newQuote['quotation_number'] = $quote->DocNumber;
        $newQuote['date'] = date('Y-m-d', strtotime($quote->TxnDate));
        $newQuote['sync_token'] = $quote->SyncToken;
        $newQuote['gst_inclusive'] = $this->parseTaxCalculation($quote->GlobalTaxCalculation);
        if ($quote->MetaData->LastUpdatedTime) {
            $updatedAt = Carbon::parse($quote->MetaData->LastUpdatedTime);
            $updatedAt->setTimezone('UTC');
            $newQuote['updated_at'] = $updatedAt->toDateTimeString();
        }
        $newQuote = $this->parseContact($quote->CustomerRef, $newQuote);
        $newQuote = $this->parseLineItems($quote->Line, $newQuote);

        if ($quote->ExpirationDate)
        {
            $newQuote['expiry_date'] = date('Y-m-d', strtotime($quote->ExpirationDate));
        }
        if ($quote->AcceptedDate)
        {
            $newQuote['accepted_date'] = date('Y-m-d', strtotime($quote->AcceptedDate));
        }

        if ($quote->BillAddr) {
            $newQuote['address'] = [
                'address_type' =>  'BILLING',
                'address_line_1' => $quote->BillAddr->Line1,
                'city' => $quote->BillAddr->City,
                'postal_code' => $quote->BillAddr->PostalCode,
                'country' => $quote->BillAddr->Country
            ];
        }

        return $newQuote;
    }

    /**
     * Return all Quotations with Generic Schema Variable Assignment
     * @return array
     */
    public function getQuotations(){
        $quotes = [];
        if ($this->data instanceof IPPEstimate){
            $newQuote = $this->parseData($this->data);
            $quotes[] = $newQuote;
        } else {
            foreach ($this->data as $quote) {
                $newQuote = $this->parseData($quote);
                $quotes[] = $newQuote;
            }
        }

        return $quotes;
    }

}
