<?php


namespace PHPAccounting\Quickbooks\Message\Quotations\Responses;


use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use QuickBooksOnline\API\Data\IPPEstimate;

class UpdateQuotationResponse extends AbstractResponse
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
                    'Quote');
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
     * Add LineItems to Quote
     * @param $data
     * @param $quote
     * @return mixed
     */
    private function parseLineItems($data, $quote) {
        if ($data) {
            $lineItems = [];
            foreach($data as $lineItem) {
                if ($lineItem->Id) {
                    $newLineItem = [];
                    $newLineItem['description'] = $lineItem->Description;
                    $newLineItem['accounting_id'] = $lineItem->Id;
                    $newLineItem['quantity'] = 1;
                    $newLineItem['unit_amount'] = 0;
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
                        $newLineItem['tax_type'] = $lineItem->SalesItemLineDetail->TaxCodeRef;
                    }
                    $subtotal += $newLineItem['line_amount'];
                    array_push($lineItems, $newLineItem);
                } elseif ($lineItem->DiscountLineDetail) {
                    if ($lineItem->DiscountLineDetail->PercentBased) {
                        $quote['discount_rate'] = $lineItem->DiscountLineDetail->DiscountPercent;
                    }
                    $quote['discount_amount'] = $lineItem->Amount;
                }

                array_push($lineItems, $newLineItem);
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

    /**
     * Return all Quotations with Generic Schema Variable Assignment
     * @return array
     */
    public function getQuotations(){
        $quotes = [];
        if ($this->data instanceof IPPEstimate){
            $quote = $this->data;
            $newQuote = [];
            $newQuote['address'] = [];
            $newQuote['accounting_id'] = $quote->Id;
            $newQuote['status'] = $quote->TxnStatus;
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

            array_push($quotes, $newQuote);

        } else {
            foreach ($this->data as $quote) {
                $newQuote = [];
                $newQuote['address'] = [];
                $newQuote['accounting_id'] = $quote->Id;
                $newQuote['status'] = $quote->TxnStatus;
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

                array_push($quotes, $newQuote);
            }
        }

        return $quotes;
    }

}