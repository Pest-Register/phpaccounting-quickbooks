<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 1/10/2019
 * Time: 11:41 AM
 */

namespace PHPAccounting\XERO\Message\ManualJournals\Request;


use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\XERO\Message\ManualJournals\Response\UpdateManualJournalResponse;
use QuickBooksOnline\API\Facades\JournalEntry;

class UpdateManualJournalRequest extends AbstractRequest
{
    /**
     * Get Narration Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getNarration(){
        return $this->getParameter('narration');
    }

    /**
     * Set Narration Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return UpdateManualJournalRequest
     */
    public function setNarration($value){
        return $this->setParameter('narration', $value);
    }

    /**
     * Get Journal Data Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getJournalData(){
        return $this->getParameter('journal_data');
    }

    /**
     * Set Journal Data Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return UpdateManualJournalRequest
     */
    public function setJournalData($value){
        return $this->setParameter('journal_data', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return CreateManualJournalRequest
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * Set Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Date
     * @return UpdateManualJournalRequest
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
     * @return UpdateManualJournalRequest
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

    private function addJournalLinesToJournal($data) {
        $lines = [];
        foreach($data as $lineData) {

            $lineItem = [];
            $lineItem['JournalEntryLineDetail'] = [];
            $lineItem['JournalEntryLineDetail']['PostingType'] = $lineData['credit'] == true? 'Credit' : 'Debit';
            $lineItem['JournalEntryLineDetail']['AccountRef'] = [];
            $lineItem['JournalEntryLineDetail']['AccountRef']['name'] = IndexSanityCheckHelper::indexSanityCheck('account_code',$lineData);
            $lineItem['JournalEntryLineDetail']['AccountRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('account_id',$lineData);
            $lineItem['Id'] = IndexSanityCheckHelper::indexSanityCheck('accounting_id',$lineData);
            $lineItem["Description"] = IndexSanityCheckHelper::indexSanityCheck('description',$lineData);
            $lineItem["Amount"] = IndexSanityCheckHelper::indexSanityCheck('gross_amount',$lineData);
            $lineItem["DetailType"] = "JournalEntryLineDetail";
            array_push($lines, $lineItem);
        }
        return $lines;
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
        $this->validate('journal_data', 'accounting_id');
        $this->issetParam('Line', 'journal_data');

        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return UpdateManualJournalResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();
        $updateParams = [];

        $updateParams['Line'] = $this->addJournalLinesToJournal($data['Line']);

        $id = $this->getAccountingID();
        try {
            $targetItem = $quickbooks->Query("select * from JournalEntry where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'detail' => $exception->getMessage()
            ]);
        }

        if (!empty($targetItem) && sizeof($targetItem) == 1) {
            $item = Invoice::update(current($targetItem), $updateParams);
            $response = $quickbooks->Update($item);
        } else {
            return $this->createResponse([
                'status' => 'error',
                'detail' => 'Existing Invoice not Found'
            ]);
        }


        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }

        return $this->createResponse($response);
    }


    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return UpdateManualJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateManualJournalResponse($this, $data);
    }
}