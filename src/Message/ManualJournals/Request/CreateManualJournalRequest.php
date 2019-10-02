<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 1/10/2019
 * Time: 11:40 AM
 */

namespace PHPAccounting\XERO\Message\ManualJournals\Request;


use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\XERO\Message\ManualJournals\Response\CreateManualJournalResponse;
use QuickBooksOnline\API\Facades\JournalEntry;

class CreateManualJournalRequest extends AbstractRequest
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
     * @return CreateManualJournalRequest
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
     * @return CreateManualJournalRequest
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
     * @return CreateManualJournalRequest
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    private function addJournalLinesToJournal($data) {
        $lines = [];
        foreach($data as $lineData) {

            $lineItem = [];
            $lineItem['JournalEntryLineDetail'] = [
                'PostingType' => $lineData['credit'] == true? 'Credit' : 'Debit',
                'AccountRef' => [
                    'name' => IndexSanityCheckHelper::indexSanityCheck('account_code',$lineData),
                    'value' => IndexSanityCheckHelper::indexSanityCheck('account_id',$lineData)
                ]
            ];

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
        $this->validate('journal_data');
        $this->issetParam('Line', 'journal_data');

        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return CreateManualJournalResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();
        $createParams = [];

        $createParams['Line'] = $this->addJournalLinesToJournal($data['Line']);

        $manualJournal = JournalEntry::create($createParams);
        $response = $quickbooks->Add($manualJournal);
        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }

        return $this->createResponse($response);
    }


    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return CreateManualJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateManualJournalResponse($this, $data);
    }
}