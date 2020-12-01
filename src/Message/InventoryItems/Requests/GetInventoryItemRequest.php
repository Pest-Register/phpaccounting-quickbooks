<?php

namespace PHPAccounting\Quickbooks\Message\InventoryItems\Requests;

use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\InventoryItems\Responses\GetInventoryItemResponse;

/**
 * Get Inventory Items(s)
 * @package PHPAccounting\Quickbooks\Message\InventoryItems\Requests
 */
class GetInventoryItemRequest extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (ID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return GetInventoryItemRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return GetInventoryItemRequest
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
     * Set SearchTerm from Parameter Bag (interface for query-based searching)
     * @see https://developer.intuit.com/app/developer/qbo/docs/develop/explore-the-quickbooks-online-api/data-queries
     * @param $value
     * @return GetInventoryItemRequest
     */
    public function setSearchTerm($value) {
        return $this->setParameter('search_term', $value);
    }

    /**
     * Set SearchParam from Parameter Bag (interface for query-based searching)
     * @see https://developer.intuit.com/app/developer/qbo/docs/develop/explore-the-quickbooks-online-api/data-queries
     * @param $value
     * @return GetInventoryItemRequest
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
     * @return GetInventoryItemResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();

        if ($this->getAccountingID()) {
            $items = $quickbooks->FindById('item', $this->getAccountingID());
            $response = $items;
        } else {
            if($this->getSearchParam() && $this->getSearchTerm())
            {
                // Set contains query for partial matching
                $response = $quickbooks->Query("SELECT * FROM Item WHERE ".$this->getSearchParam()." LIKE '%".$this->getSearchTerm()."%'");
            } else {
                $response = $quickbooks->FindAll('item', $this->getPage(), 500);
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
     * @return GetInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetInventoryItemResponse($this, $data);
    }
}