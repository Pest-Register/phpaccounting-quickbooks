<?php

namespace PHPAccounting\Quickbooks\Message\InventoryItems\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\InventoryItems\Responses\GetInventoryItemResponse;
use PHPAccounting\Quickbooks\Traits\GetRequestTrait;

/**
 * Get Inventory Items(s)
 * @package PHPAccounting\Quickbooks\Message\InventoryItems\Requests
 */
class GetInventoryItemRequest extends AbstractQuickbooksRequest
{
    use GetRequestTrait;

    public string $model = 'InventoryItem';

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetInventoryItemResponse
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
            $items = $quickbooks->FindById('item', $this->getAccountingID());
            $response = $items;
        } else {
            if($this->getSearchParams())
            {
                $query = SearchBuilder::buildSearchQuery(
                    'Item',
                    $this->getSearchParams(),
                    $this->getExactSearchValue(),
                    $this->getSearchFilters(),
                    $this->getMatchAllFilters()
                );
                // Set contains query for partial matching
                $response = $quickbooks->Query($query, $this->getPage(), 500);
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

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}