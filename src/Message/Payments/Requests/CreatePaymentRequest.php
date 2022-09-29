<?php

namespace PHPAccounting\Quickbooks\Message\Payments\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Payments\Responses\CreatePaymentResponse;
use QuickBooksOnline\API\Facades\Payment;

/**
 * Create Invoice
 * @package PHPAccounting\Quickbooks\Message\Invoices\Requests
 */
class CreatePaymentRequest extends AbstractQuickbooksRequest
{
    public string $model = 'Payment';

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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
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
     * @return CreatePaymentRequest
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    private function addCreditNoteToPayment($payment, $value, $totalAmount) {
        if (array_key_exists('accounting_id', $value)) {
            $invoice = [
                'Amount' => $totalAmount,
                'LinkedTxn' => [
                    'TxnId' => $value['accounting_id'],
                    'TxnType' => 'CreditMemo'
                ]
            ];
            array_push($payment['Line'], $invoice);
        }
        return $payment;
    }

    private function addInvoiceToPayment($payment, $value, $paymentAmount) {
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

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        try {
            $this->validate('amount', 'date');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('TotalAmt', 'amount');
        $this->issetParam('PaymentRefNum', 'reference_id');
        $this->issetParam('SyncToken', 'sync_token');
        $this->data['Line'] = [];
        if ($this->getAmount()) {
            if ($this->getInvoice()) {
                $this->data = $this->addInvoiceToPayment($this->data, $this->getInvoice(), $this->getAmount());
            }

            if ($this->getCreditNote()) {
                $this->data= $this->addCreditNoteToPayment($this->data, $this->getCreditNote(), $this->getAmount());
            }
        }

        if ($this->getAccount()) {
            $this->data['ARAccountRef']['value'] = $this->getAccount()['accounting_id'];
            $this->data['DepositToAccountRef']['value'] = $this->getAccount()['accounting_id'];
        }

        if ($this->getCurrency()) {
            $this->data['CurrencyRef']['value'] = $this->getCurrency();
        }

        if ($this->getContact()) {
            $this->data['CustomerRef']['value'] = $this->getContact()['accounting_id'];
        }
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreatePaymentResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            return $this->createResponse(
                $this->handleRequestException($data, 'InvalidRequestException')
            );
        }
        $quickbooks = $this->createQuickbooksDataService();
        $createParams = [];

        foreach ($data as $key => $value){
            $createParams[$key] = $data[$key];
        }

        try {
            $payment = Payment::create($createParams);
            $response = $quickbooks->Add($payment);

            $error = $quickbooks->getLastError();
            if ($error) {
                $response = ErrorParsingHelper::parseError($error);
            }
        }
        catch (\Throwable $exception) {
            $response = ErrorParsingHelper::parseQbPackageError($exception);
        }

        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return CreatePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreatePaymentResponse($this, $data);
    }


}
