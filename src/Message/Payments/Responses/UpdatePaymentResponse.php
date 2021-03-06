<?php


namespace PHPAccounting\Quickbooks\Message\Payments\Responses;


use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use QuickBooksOnline\API\Data\IPPLine;
use QuickBooksOnline\API\Data\IPPPayment;

class UpdatePaymentResponse extends AbstractResponse
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
                        'Payment');
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
                    'Payment');
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
     * Add Invoice to Payment
     * @return mixed
     */
    private function parseInvoice($data, $payment) {
        if ($data) {
            if ($data instanceof IPPLine) {
                if ($data->LinkedTxn) {
                    if ($data->LinkedTxn->TxnType === 'Invoice') {
                        $newInvoice = [];
                        $newInvoice['accounting_id'] = $data->LinkedTxn->TxnId;
                        $payment['invoice'] = $newInvoice;
                    }
                }
            } else {
                foreach($data as $transaction) {
                    if ($transaction->LinkedTxn) {
                        if ($transaction->LinkedTxn->TxnType === 'Invoice') {
                            $newInvoice = [];
                            $newInvoice['accounting_id'] = $transaction->LinkedTxn->TxnId;
                            $payment['invoice'] = $newInvoice;
                        }
                    }
                }
            }

        }

        return $payment;
    }

    /**
     * Add Account to Payment
     * @return mixed
     */
    private function parseAccount($data, $payment) {
        if ($data) {
            $newAccount = [];
            $newAccount['accounting_id'] = $data->value;
            $payment['account'] = $newAccount;
        }

        return $payment;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getPayments(){
        $payments = [];
        if ($this->data instanceof IPPPayment){
            $payment = $this->data;
            $newPayment = [];
            $newPayment['accounting_id'] = $payment->Id;
            $newPayment['date'] = $payment->TxnDate;
            $newPayment['amount'] = $payment->TotalAmt;
            $newPayment['reference_id'] = $payment->PaymentRefNum;
            $newPayment['currency'] = $payment->CurrencyRef;
            $newPayment['type'] = 'ACCRECPAYMENT';
            $newPayment['sync_token'] = $payment->SyncToken;
            $newPayment['status'] = $payment->TxnStatus;
            $newPayment['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $payment->MetaData->LastUpdatedTime)->toDateTimeString();
            $newPayment = $this->parseAccount($payment->ARAccountRef, $newPayment);
            $newPayment = $this->parseInvoice($payment->Line, $newPayment);

            array_push($payments, $newPayment);

        } else {
            foreach ($this->data as $payment) {
                $newPayment = [];
                $newPayment['accounting_id'] = $payment->Id;
                $newPayment['date'] = $payment->TxnDate;
                $newPayment['amount'] = $payment->TotalAmt;
                $newPayment['reference_id'] = $payment->PaymentRefNum;
                $newPayment['currency'] = $payment->CurrencyRef;
                $newPayment['type'] = 'ACCRECPAYMENT';
                $newPayment['sync_token'] = $payment->SyncToken;
                $newPayment['status'] = $payment->TxnStatus;
                $newPayment['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $payment->MetaData->LastUpdatedTime)->toDateTimeString();
                $newPayment = $this->parseAccount($payment->ARAccountRef, $newPayment);
                $newPayment = $this->parseInvoice($payment->Line, $newPayment);

                array_push($payments, $newPayment);
            }
        }

        return $payments;
    }
}