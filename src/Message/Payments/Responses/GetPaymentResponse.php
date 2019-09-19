<?php

namespace PHPAccounting\Quickbooks\Message\Payments\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use QuickBooksOnline\API\Data\IPPPayment;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Payment;

/**
 * Get Invoice(s) Response
 * @package PHPAccounting\Quickbooks\Message\Invoices\Responses
 */
class GetPaymentResponse extends AbstractResponse
{

    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if (array_key_exists('error', $this->data)) {
            if ($this->data['error']['status']){
                return false;
            }
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data['error']['status']){
            if (strpos($this->data['error']['detail'], 'Token expired') !== false) {
                return 'The access token has expired';
            } else {
                return $this->data['error']['detail'];
            }
        }

        return null;
    }

    /**
     * Add Invoice to Payment
     * @return mixed
     */
    private function parseInvoice($data, $payment) {
        if ($data) {
            if ($data->LinkedTxn) {
                if ($data->LinkedTxn->TxnType === 'Invoice') {
                    $newInvoice = [];
                    $newInvoice['accounting_id'] = $data->LinkedTxn->TxnId;
                    $payment['invoice'] = $newInvoice;
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
            $newPayment['type'] = $payment->PaymentType;
            $newPayment['status'] = $payment->TxnStatus;
            $newPayment['updated_at'] = $payment->MetaData->LastUpdatedTime;
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
                $newPayment['type'] = $payment->PaymentType;
                $newPayment['status'] = $payment->TxnStatus;
                $newPayment['updated_at'] = $payment->MetaData->LastUpdatedTime;
                $newPayment = $this->parseAccount($payment->ARAccountRef, $newPayment);
                $newPayment = $this->parseInvoice($payment->Line, $newPayment);

                array_push($payments, $newPayment);
            }
        }

        return $payments;
    }
}