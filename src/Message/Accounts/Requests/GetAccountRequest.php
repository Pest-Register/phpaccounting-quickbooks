<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Requests;

use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\Accounts\Responses\GetAccountResponse;
use QuickBooksOnline\API\Exception\IdsException;


/**
 * Get Account(s)
 * @package PHPAccounting\Quickbooks\Message\Accounts\Requests
 */
class GetAccountRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param $value
     * @return GetAccountRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param $value
     * @return GetAccountRequest
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
     * @return \Omnipay\Common\Message\ResponseInterface|GetAccountResponse
     * @throws IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();

        if ($this->getAccountingID()) {
            if ($this->getAccountingID() !== "") {
                $response = $quickbooks->FindById('account', $this->getAccountingID());
            }
        } else {
            $response = $quickbooks->FindAll('account', $this->getPage(), 500);
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
     * @return GetAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetAccountResponse($this, $data);
    }
}