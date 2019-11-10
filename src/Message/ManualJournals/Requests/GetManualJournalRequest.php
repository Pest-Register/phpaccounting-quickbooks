<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 30/09/2019
 * Time: 3:10 PM
 */

namespace PHPAccounting\Quickbooks\Message\ManualJournals\Requests;


use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\ManualJournals\Response\GetManualJournalResponse;

class GetManualJournalRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param $value
     * @return GetManualJournalRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param $value
     * @return GetManualJournalRequest
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Accounting ID (AccountID)
     * @return mixed comma-delimited-string
     */
    public function getAccountingID() {
        if ($this->getParameter('accounting_id')) {
            return $this->getParameter('accounting_id');
        }
        return null;
    }

    /**
     * Return Page Value for Pagination
     * @return integer
     */
    public function getPage() {
        return $this->getParameter('page');
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetManualJournalResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();

        if ($this->getAccountingID()) {
            if ($this->getAccountingID() !== "") {
                $response = $quickbooks->FindById('journalentry', $this->getAccountingID());
            }
        } else {
            $response = $quickbooks->FindAll('journalentry', $this->getPage(), 500);
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
     * @return GetManualJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetManualJournalResponse($this, $data);
    }
}