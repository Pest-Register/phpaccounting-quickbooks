<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 1/10/2019
 * Time: 11:40 AM
 */

namespace PHPAccounting\Quickbooks\Message\ManualJournals\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\ManualJournals\Response\CreateManualJournalResponse;
use QuickBooksOnline\API\Facades\JournalEntry;

class CreateManualJournalRequest extends AbstractQuickbooksRequest
{
    public string $model = 'ManualJournal';

    /**
     * Get Narration Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/journalentry
     * @return mixed
     */
    public function getNarration(){
        return $this->getParameter('narration');
    }

    /**
     * Set Narration Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/journalentry
     * @param string $value Status
     * @return CreateManualJournalRequest
     */
    public function setNarration($value){
        return $this->setParameter('narration', $value);
    }

    /**
     * Get Narration Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/journalentry
     * @return mixed
     */
    public function getReferenceId(){
        return $this->getParameter('reference_id');
    }

    /**
     * Set Narration Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/journalentry
     * @param string $value Status
     * @return CreateManualJournalRequest
     */
    public function setReferenceId($value){
        return $this->setParameter('reference_id', $value);
    }
    /**
     * Get Journal Data Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/journalentry
     * @return mixed
     */
    public function getJournalData(){
        return $this->getParameter('journal_data');
    }

    /**
     * Set Journal Data Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/journalentry
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
            $lineItem['JournalEntryLineDetail']['PostingType'] = $lineData['is_credit'] == true ? 'Credit' : 'Debit';
            if(array_key_exists('tax_type_id', $lineItem)){
                $lineItem['JournalEntryLineDetail']['TaxCodeRef'] = [];
//                $lineItem['JournalEntryLineDetail']['TaxCodeRef']['name'] = IndexSanityCheckHelper::indexSanityCheck('tax_type',$lineData);
                $lineItem['JournalEntryLineDetail']['TaxCodeRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('tax_type_id',$lineData);
            }
            $lineItem['JournalEntryLineDetail']['AccountRef'] = [];
            $lineItem['JournalEntryLineDetail']['AccountRef']['name'] = IndexSanityCheckHelper::indexSanityCheck('account_code',$lineData);
            $lineItem['JournalEntryLineDetail']['AccountRef']['value'] = IndexSanityCheckHelper::indexSanityCheck('account_id',$lineData);
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
        try {
            $this->validate('journal_data');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }
        $this->issetParam('Line', 'journal_data');
        $this->issetParam('PrivateNote', 'narration');
        $this->issetParam('DocNumber', 'reference_id');

        if ($this->getJournalData()) {
            $this->data['Line'] = $this->addJournalLinesToJournal($this->getJournalData());
        }
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return CreateManualJournalResponse
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
            $manualJournal = JournalEntry::create($createParams);
            $response = $quickbooks->Add($manualJournal);
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
     * @return CreateManualJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateManualJournalResponse($this, $data);
    }
}
