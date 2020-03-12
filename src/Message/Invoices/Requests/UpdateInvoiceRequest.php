<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Requests;

use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\Invoices\Responses\UpdateInvoiceResponse;
use QuickBooksOnline\API\Facades\Invoice;


/**
 * Update Invoice(s)
 * @package PHPAccounting\Quickbooks\Message\Invoices\Requests
 */
class UpdateInvoiceRequest extends AbstractRequest
{
    /**
     * Get Deposit Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getDepositAmount(){
        return $this->getParameter('deposit_amount');
    }

    /**
     * Set Deposit Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value Deposit Amount
     * @return UpdateInvoiceRequest
     */
    public function setDepositAmount($value){
        return $this->setParameter('deposit_amount', $value);
    }

    /**
     * Get Deposit Account Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getDepositAccount(){
        return $this->getParameter('deposit_account');
    }

    /**
     * Set Deposit Account Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value Deposit Account
     * @return UpdateInvoiceRequest
     */
    public function setDepositAccount($value){
        return $this->setParameter('deposit_account', $value);
    }

    /**
     * Get Discount Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getDiscountAmount(){
        return $this->getParameter('discount_amount');
    }

    /**
     * Set Discount Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value Discount Amount
     * @return UpdateInvoiceRequest
     */
    public function setDiscountAmount($value){
        return $this->setParameter('discount_amount', $value);
    }

    /**
     * Get Discount Rate Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getDiscountRate(){
        return $this->getParameter('discount_rate');
    }

    /**
     * Set Discount Rate Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value Discount Rate
     * @return UpdateInvoiceRequest
     */
    public function setDiscountRate($value){
        return $this->setParameter('discount_rate', $value);
    }

    /**
     * Get GST Inclusive Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getGSTInclusive(){
        return $this->getParameter('gst_inclusive');
    }

    /**
     * Set GST Inclusive Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value GST Inclusive
     * @return UpdateInvoiceRequest
     */
    public function setGSTInclusive($value){
        return $this->setParameter('gst_inclusive', $value);
    }

    /**
     * Get Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getSyncToken(){
        return $this->getParameter('sync_token');
    }

    /**
     * Set Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value Account Code
     * @return UpdateInvoiceRequest
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
    }

    /**
     * Get Type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getType(){
        return $this->getParameter('type');
    }

    /**
     * Set Type from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setType($value){
        return $this->setParameter('type', $value);
    }

    /**
     * Get InvoiceData Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getInvoiceData(){
        return $this->getParameter('invoice_data');
    }

    /**
     * Set Invoice Data from Parameter Bag (LineItems generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setInvoiceData($value){
        return $this->setParameter('invoice_data', $value);
    }

    /**
     * Get Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * Set Date from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    /**
     * Get Due Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getDueDate(){
        return $this->getParameter('due_date');
    }

    /**
     * Set Due Date from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setDueDate($value){
        return $this->setParameter('due_date', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value Invoice Due Date
     * @return UpdateInvoiceRequest
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get ContactParameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getContact(){
        return $this->getParameter('contact');
    }

    /**
     * Set Contact from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setContact($value){
        return $this->setParameter('contact', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * @return mixed
     */
    public function getAddress() {
        return $this->getParameter('address');
    }

    /**
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setAddress($value) {
        return $this->setParameter('address', $value);
    }


    /**
     * Add Line Items to Invoice
     * @param array $data Array of Line Items
     * @return array
     */
    private function addLineItemsToInvoice($data){
        $lineItems = [];
        $counter = 1;
        foreach($data as $lineData) {
            $lineItem = [];
            $lineItem['LineNum'] = $counter;
            $lineItem['Description'] = IndexSanityCheckHelper::indexSanityCheck('description', $lineData);

            if (array_key_exists('item_id', $lineData)) {
                $lineItem['Amount'] = IndexSanityCheckHelper::indexSanityCheck('amount', $lineData);
                $lineItem['DetailType'] = 'SalesItemLineDetail';
                $lineItem['SalesItemLineDetail'] = [];
                $lineItem['SalesItemLineDetail']['ItemAccountRef'] = [];
                $lineItem['SalesItemLineDetail']['Qty'] = IndexSanityCheckHelper::indexSanityCheck('quantity', $lineData);
                $lineItem['SalesItemLineDetail']['UnitPrice'] = IndexSanityCheckHelper::indexSanityCheck('unit_amount', $lineData);
                $lineItem['SalesItemLineDetail']['ItemRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('item_id', $lineData);
                $lineItem['SalesItemLineDetail']['TaxCodeRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('tax_id', $lineData);
                $lineItem['SalesItemLineDetail']['DiscountRate'] = IndexSanityCheckHelper::indexSanityCheck('discount_rate', $lineData);
                $lineItem['SalesItemLineDetail']['ItemAccountRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('account_id', $lineData);
            }

            if (array_key_exists('discount_amount', $lineData)) {
                $discountLineItem = [];
                $discountLineItem['LineNum'] = $counter + 1;
                $discountLineItem['Amount'] = IndexSanityCheckHelper::indexSanityCheck('discount_amount', $lineData);
                $discountLineItem['DetailType'] = 'DiscountLineDetail';
                $discountLineItem['DiscountLineDetail'] = [];
                array_push($lineItems, $discountLineItem);
            }

            array_push($lineItems, $lineItem);
        }
        return $lineItems;
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
        $this->validate('type', 'contact', 'invoice_data');

        $this->issetParam('Id', 'accounting_id');
        $this->issetParam('TxnDate', 'date');
        $this->issetParam('DueDate', 'due_date');
        $this->issetParam('DocNumber', 'invoice_number');
        $this->issetParam('TotalAmt', 'total');
        $this->issetParam('SyncToken', 'sync_token');
        $this->issetParam('Deposit', 'deposit_amount');

        $this->data['sparse'] = True;

        if ($this->getInvoiceData()) {
            $this->data['Line'] = $this->addLineItemsToInvoice($this->getInvoiceData());
        }

        if ($this->getDepositAccount()) {
            $this->data['DepositToAccountRef']['value'] = $this->getDepositAccount();
        }

        if ($this->getDiscountAmount()) {
            $discountLineItem = [];
            $discountLineItem['LineNum'] = 1;
            $discountLineItem['Description'] = '';
            $discountLineItem['Amount'] = $this->getDiscountAmount();
            $discountLineItem['DetailType'] = 'DiscountLineDetail';
            $discountLineItem['DiscountLineDetail']['PercentBased'] = false;
            array_push($this->data['Line'], $discountLineItem);
        }

        if ($this->getContact()) {
            $this->data['CustomerRef'] = [
                'value' => $this->getContact()
            ];
        }

        if ($this->getStatus()) {
            if ($this->getStatus() === 'OPEN') {
                $this->data['EmailStatus'] = 'EmailSent';
            } else {
                $this->data['EmailStatus'] = 'NotSet';
            }
        }

        if ($this->getGSTInclusive()) {
            if ($this->getGSTInclusive() === 'EXCLUSIVE') {
                $this->data['GlobalTaxCalculation'] = 'TaxExcluded';
            }
            if ($this->getGSTInclusive() === 'INCLUSIVE') {
                $this->data['GlobalTaxCalculation'] = 'TaxInclusive';
            }
            if ($this->getGSTInclusive() === 'NONE') {
                $this->data['GlobalTaxCalculation'] = 'NotApplicable';
            }
        }

        if ($this->getAddress()) {
            $address = $this->getAddress();
            $this->data['BillAddr'] =
                [
                    'Line1' => IndexSanityCheckHelper::indexSanityCheck('address_line_1', $address),
                    'City' => IndexSanityCheckHelper::indexSanityCheck('city', $address),
                    'Country' => IndexSanityCheckHelper::indexSanityCheck('country', $address),
                    'PostalCode' => IndexSanityCheckHelper::indexSanityCheck('postal_code', $address)
                ];
        }

        $this->data['ApplyTaxAfterDiscount'] = true;
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return UpdateInvoiceResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();
        $updateParams = [];

        foreach ($data as $key => $value){
            $updateParams[$key] = $data[$key];
        }
        $id = $this->getAccountingID();
        try {
            $targetItem = $quickbooks->Query("select * from Invoice where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'detail' => $exception->getMessage()
            ]);
        }

        if (!empty($targetItem) && sizeof($targetItem) == 1) {
            $item = Invoice::update(current($targetItem),$updateParams);
            $response = $quickbooks->Update($item);
        } else {
            $error = $quickbooks->getLastError();
            if ($error) {
                $response = ErrorParsingHelper::parseError($error);
            } else {
                return $this->createResponse([
                    'status' => 'error',
                    'detail' => 'Existing Invoice not Found'
                ]);
            }

        }

        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }

        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return UpdateInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateInvoiceResponse($this, $data);
    }
}