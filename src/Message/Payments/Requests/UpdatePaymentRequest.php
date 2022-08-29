<?php


namespace PHPAccounting\Quickbooks\Message\Payments\Requests;


use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\Payments\Responses\UpdatePaymentResponse;
use QuickBooksOnline\API\Facades\Payment;

class UpdatePaymentRequest extends AbstractRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/payments
     * @param $value
     * @return UpdatePaymentRequest
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

    private function addCreditNoteToPayment( $payment, $value, $paymentAmount) {
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
            $this->validate('accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('TotalAmt', 'amount');
        $this->issetParam('PaymentRefNum', 'reference_id');
        $this->issetParam('SyncToken', 'sync_token');

        $this->data['sparse'] = true;

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
     * @return \Omnipay\Common\Message\ResponseInterface|UpdatePaymentResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = [
                'status' => 'error',
                'type' => 'InvalidRequestException',
                'detail' =>
                    [
                        'message' => $data->getMessage(),
                        'error_code' => $data->getCode(),
                        'status_code' => 422,
                    ],
            ];
            return $this->createResponse($response);
        }

        $quickbooks = $this->createQuickbooksDataService();
        $updateParams = [];

        foreach ($data as $key => $value){
            $updateParams[$key] = $data[$key];
        }

        $id = $this->getAccountingID();
        try {
            $targetItem = $quickbooks->Query("select * from Payment where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'type' => 'InvalidRequestException',
                'detail' =>
                    [
                        'message' => $exception->getMessage(),
                        'error_code' => null,
                        'status_code' => 422,
                    ],
            ]);
        }

        try {
            if (!empty($targetItem) && sizeof($targetItem) == 1) {
                $item = Payment::update(current($targetItem), $updateParams);
                $response = $quickbooks->Update($item);
            } else {
                $error = $quickbooks->getLastError();
                if ($error) {
                    $response = ErrorParsingHelper::parseError($error);
                } else {
                    return $this->createResponse([
                        'status' => 'error',
                        'type' => 'InvalidRequestException',
                        'detail' =>
                            [
                                'message' => 'Existing Payment not found',
                                'error_code' => null,
                                'status_code' => 422,
                            ],
                    ]);
                }
            }


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
     * @return UpdatePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdatePaymentResponse($this, $data);
    }
}
