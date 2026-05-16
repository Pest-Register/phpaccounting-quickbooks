<?php
namespace PHPAccounting\Quickbooks\Message\Contacts\Requests;

use PHPAccounting\Quickbooks\Foundation\Exceptions\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\Contacts\Responses\GetContactResponse;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Quickbooks\Traits\GetRequestTrait;

/**
 * Get Contact(s)
 * @package PHPAccounting\Quickbooks\Message\Contacts\Requests
 */
class GetContactRequest extends AbstractQuickbooksRequest
{
    use GetRequestTrait;

    public string $model = 'Contact';


    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \PHPAccounting\Quickbooks\Foundation\Contracts\ResponseInterface|GetContactResponse
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
        $modifiedSince = $this->getLastModifiedSince();

        if ($this->getAccountingID()) {
            $accounts = $quickbooks->FindById('customer', $this->getAccountingID());
            $response = $accounts;
        } else {
            if($this->getSearchParams() || $this->getSearchFilters() || $modifiedSince)
            {
                // Build search query with filters (and a Metadata.LastUpdatedTime
                // clause for delta sync). Forces the Query() path even when
                // there are no other filters, since FindAll() has no
                // since-cutoff equivalent.
                $query = SearchBuilder::buildSearchQuery(
                    'Customer',
                    $this->getSearchParams(),
                    $this->getExactSearchValue(),
                    $this->getSearchFilters(),
                    $this->getMatchAllFilters(),
                    $modifiedSince
                );
                // Set contains query for partial matching
                $response = $quickbooks->Query($query, $this->getPage(), 500);
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

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}