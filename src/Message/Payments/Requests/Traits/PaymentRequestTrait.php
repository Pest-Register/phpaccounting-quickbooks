<?php

namespace PHPAccounting\Quickbooks\Message\Payments\Requests\Traits;

trait PaymentRequestTrait
{
    /**
     * Get Contact Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getContact(){
        return $this->getParameter('contact');
    }

    /**
     * Set Contact Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Sync Token
     */
    public function setContact($value){
        return $this->setParameter('contact', $value);
    }

    /**
     * Get Currency Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getCurrency(){
        return $this->getParameter('currency');
    }

    /**
     * Set Currency Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Sync Token
     */
    public function setCurrency($value){
        return $this->setParameter('currency', $value);
    }
    /**
     * Get Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getSyncToken(){
        return $this->getParameter('sync_token');
    }

    /**
     * Set Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Sync Token
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
    }
    /**
     * Get Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getAmount(){
        return $this->getParameter('amount');
    }

    /**
     * Set Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Payment Amount
     */
    public function setAmount($value){
        return $this->setParameter('amount', $value);
    }

    /**
     * Get Currency Rate Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getCurrencyRate(){
        return $this->getParameter('currency_rate');
    }

    /**
     * Set Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Payment Currency Rate
     */
    public function setCurrencyRate($value){
        return $this->setParameter('currency_rate', $value);
    }

    /**
     * Get Currency Rate Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getReferenceID(){
        return $this->getParameter('reference_id');
    }

    /**
     * Set Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Payment Reference ID
     */
    public function setReferenceID($value){
        return $this->setParameter('reference_id', $value);
    }

    /**
     * Get Is Reconciled Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getIsReconciled(){
        return $this->getParameter('is_reconciled');
    }

    /**
     * Set Is Reconciled Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Payment Is Reconcile
     */
    public function setIsReconciled($value){
        return $this->setParameter('is_reconciled', $value);
    }

    /**
     * Get Invoice Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getInvoice(){
        return $this->getParameter('invoice');
    }

    /**
     * Set Invoice Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Invoice
     */
    public function setInvoice($value){
        return $this->setParameter('invoice', $value);
    }

    /**
     * Get Account Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getAccount(){
        return $this->getParameter('account');
    }

    /**
     * Set Account Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Invoice
     */
    public function setAccount($value){
        return $this->setParameter('account', $value);
    }

    /**
     * Get Credit Note Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getCreditNote(){
        return $this->getParameter('credit_note');
    }

    /**
     * Set Credit Note Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value CreditNote
     */
    public function setCreditNote($value){
        return $this->setParameter('credit_note', $value);
    }

    /**
     * Get Prepayment Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getPrepayment(){
        return $this->getParameter('prepayment');
    }

    /**
     * Set Prepayment Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Prepayment
     */
    public function setPrepayment($value){
        return $this->setParameter('prepayment', $value);
    }

    /**
     * Get Overpayment Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getOverpayment(){
        return $this->getParameter('overpayment');
    }

    /**
     * Set Overpayment Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Overpayment
     */
    public function setOverpayment($value){
        return $this->setParameter('overpayment', $value);
    }

    /**
     * Get Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * Set Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param string $value Date
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param $value
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    public function addCreditNoteToPayment( $payment, $value, $paymentAmount) {
        if (array_key_exists('accounting_id', $value)) {
            $invoice = [
                'Amount' => $paymentAmount,
                'LinkedTxn' => [
                    'TxnId' => $value['accounting_id'],
                    'TxnType' => 'CreditMemo'
                ]
            ];
            array_push($payment['Line'], $invoice);
        }

        return $payment;
    }

    public function addInvoiceToPayment($payment, $value, $paymentAmount) {
        if (array_key_exists('accounting_id', $value)) {
            $invoice = [
                'Amount' => $paymentAmount,
                'LinkedTxn' => [
                    'TxnId' => $value['accounting_id'],
                    'TxnType' => 'Invoice'
                ]
            ];
            array_push($payment['Line'], $invoice);
        }

        return $payment;
    }

}