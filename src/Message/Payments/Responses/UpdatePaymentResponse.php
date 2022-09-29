<?php


namespace PHPAccounting\Quickbooks\Message\Payments\Responses;


use Carbon\Carbon;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPLine;
use QuickBooksOnline\API\Data\IPPPayment;

class UpdatePaymentResponse extends AbstractQuickbooksResponse
{

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
     * @param $payment
     * @return mixed
     */
    private function parseData($payment) {
        $newPayment = [];
        $newPayment['accounting_id'] = $payment->Id;
        $newPayment['date'] = $payment->TxnDate;
        $newPayment['amount'] = $payment->TotalAmt;
        $newPayment['reference_id'] = $payment->PaymentRefNum;
        $newPayment['currency'] = $payment->CurrencyRef;
        $newPayment['sync_token'] = $payment->SyncToken;
        $newPayment['type'] = 'ACCRECPAYMENT';
        $newPayment['status'] = $payment->TxnStatus;
        if ($payment->MetaData->LastUpdatedTime) {
            $updatedAt = Carbon::parse($payment->MetaData->LastUpdatedTime);
            $updatedAt->setTimezone('UTC');
            $newPayment['updated_at'] = $updatedAt->toDateTimeString();
        }
        $newPayment = $this->parseAccount($payment->DepositToAccountRef, $newPayment);
        $newPayment = $this->parseInvoice($payment->Line, $newPayment);

        return $newPayment;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getPayments(){
        $payments = [];
        if ($this->data instanceof IPPPayment){
            $newPayment = $this->parseData($this->data);
            $payments[] = $newPayment;

        } else {
            foreach ($this->data as $payment) {
                $newPayment = $this->parseData($payment);
                $payments[] = $newPayment;
            }
        }

        return $payments;
    }
}
