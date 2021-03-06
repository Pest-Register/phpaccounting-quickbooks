<?php

namespace PHPAccounting\Quickbooks\Message\TaxRates\Requests;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\InventoryItems\Requests\GetInventoryItemRequest;
use PHPAccounting\Quickbooks\Message\InventoryItems\Responses\GetInventoryItemResponse;
use PHPAccounting\Quickbooks\Message\TaxRates\Responses\GetTaxRateResponse;
use PHPAccounting\Quickbooks\Message\TaxRates\Responses\GetTaxRateValuesResponse;

/**
 * Get Tax Rate(s)
 * @package PHPAccounting\Quickbooks\Message\InventoryItems\Requests
 */
class GetTaxRateRequest extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (ID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/taxrate
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/taxrate
     * @param $value
     * @return GetTaxRateRequest
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
     * Set SearchParams from Parameter Bag (interface for query-based searching)
     * @see https://www.odata.org/documentation/odata-version-3-0/odata-version-3-0-core-protocol/
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setSearchParams($value) {
        return $this->setParameter('search_params', $value);
    }
    /**
     * Return Search Parameters for query-based searching
     * @return array
     */
    public function getSearchParams() {
        return $this->getParameter('search_params');
    }

    /**
     * Set boolean to determine partial or exact query based searches
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setExactSearchValue($value) {
        return $this->setParameter('exact_search_value', $value);
    }

    /**
     * Get boolean to determine partial or exact query based searches
     * @return mixed
     */
    public function getExactSearchValue() {
        return $this->getParameter('exact_search_value');
    }

    /**
     * Set SearchFilters from Parameter Bag (interface for query-based searching)
     * @see https://developer.xero.com/documentation/api/requests-and-responses#get-modified
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setSearchFilters($value) {
        return $this->setParameter('search_filters', $value);
    }

    /**
     * Return Search Filters for query-based searching
     * @return array
     */
    public function getSearchFilters() {
        return $this->getParameter('search_filters');
    }

    /**
     * Set boolean to determine whether all filters need to be matched
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setMatchAllFilters($value) {
        return $this->setParameter('match_all_filters', $value);
    }

    /**
     * Get boolean to determine whether all filters need to be matched
     * @return mixed
     */
    public function getMatchAllFilters() {
        return $this->getParameter('match_all_filters');
    }

    /**
     * Builds search / filter query based on search parameters and filter
     */
    private function buildSearchQuery() {
        $query = "SELECT * FROM TaxCode WHERE ";
        $separationFilter = "";
        if ($this->getSearchParams()) {
            $searchParameters = $this->getSearchParams();
            foreach($searchParameters as $key => $value)
            {
                if ($this->getExactSearchValue())
                {
                    $statement = $separationFilter.$key."='".$value."'";
                } else {
                    $statement = $separationFilter.$key." LIKE '%".$value."%'";
                }

                $separationFilter = " AND ";
                $query .= $statement;
            }
        }
        $queryCounter = 0;
        if ($this->getSearchFilters()) {
            if ($this->getSearchParams())
            {
                $query.=' AND ';
            }
            foreach($this->getSearchFilters() as $key => $value) {
                $queryString = '';
                $filterKey = $key;
                if ($this->getMatchAllFilters())
                {
                    foreach($value as $filterValue) {
                        $searchQuery = $filterKey."='".$filterValue."'";
                        if ($queryCounter == 0) {
                            $queryString = $searchQuery;
                        } else {
                            $queryString.= ' AND '.$searchQuery;
                        }
                        $queryCounter++;
                    }
                } else {
                    $searchQuery = $filterKey." IN (";
                    $count = 1;
                    $queryString = $searchQuery;
                    foreach($value as $filterValue) {
                        if ($count != count($value))
                        {
                            $queryString.="'".$filterValue."', ";
                        }
                        else {
                            $queryString.="'".$filterValue."')";
                        }
                        $count++;
                    }
                }
                $query .= $queryString;
            }
        }
        return $query;
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
            if($this->getSearchParams() || $this->getSearchFilters())
            {
                // Build search query with filters (if applicable)
                $query = $this->buildSearchQuery();
                // Set contains query for partial matching
                $response = $quickbooks->Query($query);
            } else {
                $response = $quickbooks->FindAll('taxcode', $this->getPage(), 500);
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
     * @return GetTaxRateResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetTaxRateResponse($this, $data);
    }
}