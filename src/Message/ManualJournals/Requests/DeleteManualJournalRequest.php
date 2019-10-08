<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 1/10/2019
 * Time: 11:40 AM
 */

namespace PHPAccounting\XERO\Message\ManualJournals\Requests;


use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\XERO\Message\ManualJournals\Response\DeleteManualJournalResponse;
use QuickBooksOnline\API\Facades\JournalEntry;

class DeleteManualJournalRequest extends AbstractRequest
{

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
     * @return DeleteManualJournalRequest
     */
    public function setJournalData($value){
        return $this->setParameter('journal_data', $value);
    }

    /**
     * Set Journal Data Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return DeleteManualJournalRequest
     */
    public function setSyncToken($value){
        return $this->setParameter('journal_data', $value);
    }

    /**
     * Get Journal Data Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getSyncToken(){
        return $this->getParameter('journal_data');
    }

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param $value
     * @return DeleteManualJournalRequest
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
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('Id', 'syncToken');

        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return DeleteManualJournalResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();
        $journalEntry = $quickbooks->FindbyId('journalentry', $this->getAccountingID());
        $response = $quickbooks->Delete($journalEntry);
        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }

        return $this->createResponse($response);
    }


    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return DeleteManualJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteManualJournalResponse($this, $data);
    }
}