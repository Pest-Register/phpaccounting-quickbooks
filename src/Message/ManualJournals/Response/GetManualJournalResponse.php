<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 30/09/2019
 * Time: 3:10 PM
 */

namespace PHPAccounting\XERO\Message\ManualJournals\Response;


use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\XERO\Message\ManualJournals\Request\GetManualJournalRequest;

class GetManualJournalResponse extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (ID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/taxrate
     * @param $value
     * @return GetManualJournalResponse
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/taxrate
     * @param $value
     * @return GetManualJournalResponse
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Inventory Item ID (ID)
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
     * @return GetTaxRateResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();

        if ($this->getAccountingID()) {
            $items = $quickbooks->FindById('taxcode', $this->getAccountingID());
            $response = $items;
        } else {
            $response = $quickbooks->FindAll('taxcode', $this->getPage(), 500);
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
     * @return GetTaxRateResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetTaxRateResponse($this, $data);
    }
}