<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Accounts\Responses\GetAccountResponse;
use PHPAccounting\Quickbooks\Message\Invoices\Responses\GetInvoiceResponse;
use PHPAccounting\Quickbooks\Traits\GetRequestTrait;
use QuickBooksOnline\API\Exception\IdsException;

/**
 * Get Invoice(s)
 * @package PHPAccounting\Quickbooks\Message\Invoices\Requests
 */
class GetInvoiceRequest extends AbstractQuickbooksRequest
{
    use GetRequestTrait;

    public string $model = 'Invoice';


    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetAccountResponse
     * @throws IdsException
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
            if ($this->getAccountingID() !== "") {
                $response = $quickbooks->FindById('invoice', $this->getAccountingID());
            }
        } else {
            if($this->getSearchParams() || $this->getSearchFilters())
            {
                // Build search query with filters (if applicable)
                $query = SearchBuilder::buildSearchQuery(
                    'Invoice',
                    $this->getSearchParams(),
                    $this->getExactSearchValue(),
                    $this->getSearchFilters(),
                    $this->getMatchAllFilters()
                );
                // Set contains query for partial matching
                $response = $quickbooks->Query($query, $this->getPage(), 500);
            } else {
                $response = $quickbooks->FindAll('invoice', $this->getPage());
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
     * @return GetInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetInvoiceResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
