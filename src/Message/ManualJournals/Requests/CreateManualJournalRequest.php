<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 1/10/2019
 * Time: 11:40 AM
 */

namespace PHPAccounting\XERO\Message\ManualJournals\Requests;


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
     * Get Narration Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getReferenceId(){
        return $this->getParameter('reference_id');
    }

    /**
     * Set Narration Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return CreateManualJournalRequest
     */
    public function setReferenceId($value){
        return $this->setParameter('reference_id', $value);
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

    private function addJournalLinesToJournal($data) {
        $lines = [];
        foreach($data as $lineData) {

            $lineItem = [];
            $lineItem['JournalEntryLineDetail'] = [];
            $lineItem['JournalEntryLineDetail']['PostingType'] = $lineData['credit'] == true? 'Credit' : 'Debit';
            if(array_key_exists('tax_type_id', $lineItem)){
                $lineItem['JournalEntryLineDetail']['TaxCodeRef'] = [];
                $lineItem['JournalEntryLineDetail']['TaxCodeRef']['name'] = IndexSanityCheckHelper::indexSanityCheck('tax_type',$lineData);
                $lineItem['JournalEntryLineDetail']['TaxCodeRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('tax_type_id',$lineData);
            }
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
        $this->validate('journal_data');
        $this->issetParam('Line', 'journal_data');
        $this->issetParam('PrivateNote', 'narration');
        $this->issetParam('DocNumber', 'reference_id');

        if ($this->getJournalData()) {
            $this->data['Line'] = $this->addJournalLinesToJournal($this->getJournalData());
        }
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

        foreach ($data as $key => $value){
            $createParams[$key] = $data[$key];
        }

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