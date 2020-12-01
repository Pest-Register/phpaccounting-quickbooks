<?php
namespace PHPAccounting\Quickbooks\Message\Contacts\Requests;

use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\Contacts\Responses\GetContactResponse;
use PHPAccounting\Quickbooks\Message\AbstractRequest;

/**
 * Get Contact(s)
 * @package PHPAccounting\Quickbooks\Message\Contacts\Requests
 */
class GetContactRequest extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param $value
     * @return GetContactRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param $value
     * @return GetContactRequest
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
     * Set SearchTerm from Parameter Bag (interface for query-based searching)
     * @see https://developer.intuit.com/app/developer/qbo/docs/develop/explore-the-quickbooks-online-api/data-queries
     * @param $value
     * @return GetContactRequest
     */
    public function setSearchTerm($value) {
        return $this->setParameter('search_term', $value);
    }

    /**
     * Set SearchParam from Parameter Bag (interface for query-based searching)
     * @see https://developer.intuit.com/app/developer/qbo/docs/develop/explore-the-quickbooks-online-api/data-queries
     * @param $value
     * @return GetContactRequest
     */
    public function setSearchParam($value) {
        return $this->setParameter('search_param', $value);
    }

    /**
     * Return Search Parameter for query-based searching
     * @return integer
     */
    public function getSearchParam() {
        return $this->getParameter('search_param');
    }

    /**
     * Return Search Term for query-based searching
     * @return integer
     */
    public function getSearchTerm() {
        return $this->getParameter('search_term');
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetContactResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();

        if ($this->getAccountingID()) {
            $accounts = $quickbooks->FindById('customer', $this->getAccountingID());
            $response = $accounts;
        } else {
            if($this->getSearchParam() && $this->getSearchTerm())
            {
                // Set contains query for partial matching
                $response = $quickbooks->Query("SELECT * FROM Customer WHERE ".$this->getSearchParam()." LIKE '%".$this->getSearchTerm()."%'");
            } else {
                $response = $quickbooks->FindAll('customer', $this->getPage(), 500);
            }
        }

        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }

        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return GetContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetContactResponse($this, $data);
    }

}