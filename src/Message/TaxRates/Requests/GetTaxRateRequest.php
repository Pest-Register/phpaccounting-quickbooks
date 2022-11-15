<?php

namespace PHPAccounting\Quickbooks\Message\TaxRates\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\TaxRates\Responses\GetTaxRateResponse;
use PHPAccounting\Quickbooks\Traits\GetRequestTrait;

/**
 * Get Tax Rate(s)
 * @package PHPAccounting\Quickbooks\Message\InventoryItems\Requests
 */
class GetTaxRateRequest extends AbstractQuickbooksRequest
{
    use GetRequestTrait;

    public string $model = 'TaxRate';


    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetTaxRateResponse
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

        if ($this->getAccountingID()) {
            $items = $quickbooks->FindById('taxcode', $this->getAccountingID());
            $response = $items;
        } else {
            if($this->getSearchParams() || $this->getSearchFilters())
            {
                // Build search query with filters (if applicable)
                $query = SearchBuilder::buildSearchQuery(
                    'TaxCode',
                    $this->getSearchParams(),
                    $this->getExactSearchValue(),
                    $this->getSearchFilters(),
                    $this->getMatchAllFilters()
                );
                // Set contains query for partial matching
                $response = $quickbooks->Query($query, $this->getPage(), 500);
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

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}