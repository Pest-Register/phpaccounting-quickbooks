<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Requests\Traits;

use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;

trait InvoiceRequestTrait
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
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Invoice Number from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getInvoiceNumber(){
        return $this->getParameter('invoice_number');
    }

    /**
     * Get Invoice Number from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function setInvoiceNumber($value){
        return $this->setParameter('invoice_number', $value);
    }

    /**
     * Get Tax Inclusive Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getTaxInclusiveAmount(){
        return $this->getParameter('tax_inclusive_amount');
    }

    /**
     * Set Tax Inclusive Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value Invoice Due Date
     */
    public function setTaxInclusiveAmount($value){
        return $this->setParameter('tax_inclusive_amount', $value);
    }

    /**
     * Get Total Tax from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getTotalTax(){
        return $this->getParameter('total_tax');
    }

    /**
     * Get Total Tax from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function setTotalTax($value){
        return $this->setParameter('total_tax', $value);
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
     */
    public function setContact($value){
        return $this->setParameter('contact', $value);
    }

    /**
     * Get Subtotal After Tax Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getSubtotalAfterTax(){
        return $this->getParameter('sub_total_after_tax');
    }

    /**
     * Set Subtotal After Tax Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     */
    public function setSubtotalAfterTax($value){
        return $this->setParameter('sub_total_after_tax', $value);
    }

    /**
     * Get Subtotal Before Tax Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getSubtotalBeforeTax(){
        return $this->getParameter('sub_total_before_tax');
    }

    /**
     * Set Subtotal Before Tax Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     */
    public function setSubtotalBeforeTax($value){
        return $this->setParameter('sub_total_before_tax', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
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
     */
    public function setAddress($value) {
        return $this->setParameter('address', $value);
    }

    /**
     * Get Tax Lines from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getTaxLines() {
        return $this->getParameter('tax_lines');
    }

    /**
     * Set Tax Lines from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function setTaxLines($value) {
        return $this->setParameter('tax_lines', $value);
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
                $lineItem['SalesItemLineDetail']['Qty'] = IndexSanityCheckHelper::indexSanityCheck('quantity', $lineData);
                $lineItem['SalesItemLineDetail']['UnitPrice'] = IndexSanityCheckHelper::indexSanityCheck('unit_amount', $lineData);
                $lineItem['SalesItemLineDetail']['ItemRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('item_id', $lineData);
                $lineItem['SalesItemLineDetail']['TaxCodeRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('tax_id', $lineData);
                $lineItem['SalesItemLineDetail']['DiscountRate'] = IndexSanityCheckHelper::indexSanityCheck('discount_rate', $lineData);
                $lineItem['SalesItemLineDetail']['TaxInclusiveAmt'] = IndexSanityCheckHelper::indexSanityCheck('tax_inclusive_amount', $lineData);
            } else {
                $lineItem['Amount'] = IndexSanityCheckHelper::indexSanityCheck('amount', $lineData);
                $lineItem['DetailType'] = 'SalesItemLineDetail';
                $lineItem['SalesItemLineDetail'] = [];
                $lineItem['SalesItemLineDetail']['Qty'] = IndexSanityCheckHelper::indexSanityCheck('quantity', $lineData);
                $lineItem['SalesItemLineDetail']['UnitPrice'] = IndexSanityCheckHelper::indexSanityCheck('unit_amount', $lineData);
                $lineItem['SalesItemLineDetail']['TaxCodeRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('tax_id', $lineData);
                $lineItem['SalesItemLineDetail']['DiscountRate'] = IndexSanityCheckHelper::indexSanityCheck('discount_rate', $lineData);
                $lineItem['SalesItemLineDetail']['TaxInclusiveAmt'] = IndexSanityCheckHelper::indexSanityCheck('tax_inclusive_amount', $lineData);
            }
            $counter++;
            array_push($lineItems, $lineItem);
        }
        if ($this->getDiscountRate()) {
            if ($this->getDiscountRate() > 0) {
                $discountLineItem = [];
                $discountLineItem['LineNum'] = $counter;
                $discountLineItem['Description'] = '';
                $discountLineItem['Amount'] = $this->getDiscountAmount();
                $discountLineItem['DetailType'] = 'DiscountLineDetail';
                $discountLineItem['DiscountLineDetail']['PercentBased'] = true;
                $discountLineItem['DiscountLineDetail']['DiscountPercent'] = $this->getDiscountRate();
                array_push($lineItems, $discountLineItem);
            }
        }
        else if ($this->getDiscountAmount()) {
            if ($this->getDiscountAmount() > 0) {
                $discountLineItem = [];
                $discountLineItem['LineNum'] = $counter;
                $discountLineItem['Description'] = '';
                $discountLineItem['Amount'] = $this->getDiscountAmount();
                $discountLineItem['DetailType'] = 'DiscountLineDetail';
                $discountLineItem['DiscountLineDetail']['PercentBased'] = false;
                array_push($lineItems, $discountLineItem);
            }
        }
        return $lineItems;
    }
}