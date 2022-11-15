<?php

namespace PHPAccounting\Quickbooks\Message\Quotations\Requests\Traits;

use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;

trait QuotationRequestTrait
{
    /**
     * Get Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getSyncToken(){
        return $this->getParameter('sync_token');
    }

    /**
     * Set Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Deposit Amount
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
    }

    /**
     * Get Deposit Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getDepositAmount(){
        return $this->getParameter('deposit_amount');
    }

    /**
     * Set Deposit Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Deposit Amount
     */
    public function setDepositAmount($value){
        return $this->setParameter('deposit_amount', $value);
    }

    /**
     * Get Deposit Account Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getDepositAccount(){
        return $this->getParameter('deposit_account');
    }

    /**
     * Set Deposit Account Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Deposit Account
     */
    public function setDepositAccount($value){
        return $this->setParameter('deposit_account', $value);
    }

    /**
     * Get Discount Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getDiscountAmount(){
        return $this->getParameter('discount_amount');
    }

    /**
     * Set Discount Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Discount Amount
     */
    public function setDiscountAmount($value){
        return $this->setParameter('discount_amount', $value);
    }

    /**
     * Get Discount Rate Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getDiscountRate(){
        return $this->getParameter('discount_rate');
    }

    /**
     * Set Discount Rate Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Discount Rate
     */
    public function setDiscountRate($value){
        return $this->setParameter('discount_rate', $value);
    }

    /**
     * Get GST Inclusive Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getGSTInclusive(){
        return $this->getParameter('gst_inclusive');
    }

    /**
     * Set GST Inclusive Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value GST Inclusive
     */
    public function setGSTInclusive($value){
        return $this->setParameter('gst_inclusive', $value);
    }

    /**
     * Get Total Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getTotal(){
        return $this->getParameter('total');
    }

    /**
     * Set Total Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Total
     */
    public function setTotal($value){
        return $this->setParameter('total', $value);
    }

    /**
     * Get Quotation Data Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getQuotationData(){
        return $this->getParameter('quotation_data');
    }

    /**
     * Set Quotation Data Parameter from Parameter Bag (LineItems)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param array $value Quotation Item Lines
     */
    public function setQuotationData($value){
        return $this->setParameter('quotation_data', $value);
    }

    /**
     * Get Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * Set Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Quotation date
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Quote Due Date
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Tax Inclusive Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getTaxInclusiveAmount(){
        return $this->getParameter('tax_inclusive_amount');
    }

    /**
     * Set Tax Inclusive Amount Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Tax Inclusive Amount
     */
    public function setTaxInclusiveAmount($value){
        return $this->setParameter('tax_inclusive_amount', $value);
    }

    /**
     * Get Expiry Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getExpiryDate(){
        return $this->getParameter('expiry_date');
    }

    /**
     * Set Expiry Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Quotation Expiry Date
     */
    public function setExpiryDate($value){
        return $this->setParameter('expiry_date', $value);
    }

    /**
     * Get Accepted Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getAcceptedDate(){
        return $this->getParameter('accepted_date');
    }

    /**
     * Set Accepted Date Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Quotation Accepted Date
     */
    public function setAcceptedDate($value){
        return $this->setParameter('accepted_date', $value);
    }

    /**
     * Get Total Tax from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getTotalTax(){
        return $this->getParameter('total_tax');
    }

    /**
     * Get Total Tax from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function setTotalTax($value){
        return $this->setParameter('total_tax', $value);
    }

    /**
     * Get Contact Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getContact(){
        return $this->getParameter('contact');
    }

    /**
     * Set Contact Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param Contact $value Contact
     */
    public function setContact($value){
        return $this->setParameter('contact', $value);
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
     * Get Quotation Number from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getQuotationNumber(){
        return $this->getParameter('quotation_number');
    }

    /**
     * Get Quotation Number from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function setQuotationNumber($value){
        return $this->setParameter('quotation_number', $value);
    }

    /**
     * Get Tax Lines from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getTaxLines() {
        return $this->getParameter('tax_lines');
    }

    /**
     * Set Tax Lines from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function setTaxLines($value) {
        return $this->setParameter('tax_lines', $value);
    }

    /**
     * Get Title Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getTitle(){
        return $this->getParameter('title');
    }

    /**
     * Set Title Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Title
     */
    public function setTitle($value){
        return $this->setParameter('title', $value);
    }

    /**
     * Get Summary Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getSummary(){
        return $this->getParameter('summary');
    }

    /**
     * Set Summary Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Summary
     */
    public function setSummary($value){
        return $this->setParameter('summary', $value);
    }

    /**
     * Get Terms Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getTerms(){
        return $this->getParameter('terms');
    }

    /**
     * Set Terms Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param string $value Terms
     */
    public function setTerms($value){
        return $this->setParameter('terms', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @param $value
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/estimates
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Add Line Items to Quotation
     * @param array $data Array of Line Items
     * @return array
     */
    private function addLineItemsToQuotation($data){
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
//                $lineItem['SalesItemLineDetail']['ItemAccountRef'] = [];
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
//                $lineItem['SalesItemLineDetail']['ItemAccountRef'] = [];
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
                $discountLineItem['Amount'] = $this->getDiscountRate();
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