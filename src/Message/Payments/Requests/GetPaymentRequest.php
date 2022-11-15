<?php

namespace PHPAccounting\Quickbooks\Message\Payments\Requests;
use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Payments\Responses\GetPaymentResponse;
use PHPAccounting\Quickbooks\Traits\GetRequestTrait;

/**
 * Get Invoice(s)
 * @package PHPAccounting\Quickbooks\Message\Invoices\Requests
 */
class GetPaymentRequest extends AbstractQuickbooksRequest
{
    use GetRequestTrait;

    public string $model = 'Payment';
    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetPaymentResponse
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
                $response = $quickbooks->FindById('payment', $this->getAccountingID());
            }
        } else {
            if($this->getSearchParams() || $this->getSearchFilters())
            {
                // Build search query with filters (if applicable)
                $query = SearchBuilder::buildSearchQuery(
                    'Payment',
                    $this->getSearchParams(),
                    $this->getExactSearchValue(),
                    $this->getSearchFilters(),
                    $this->getMatchAllFilters()
                );
                // Set contains query for partial matching
                $response = $quickbooks->Query($query, $this->getPage(), 500);
            } else {
                $response = $quickbooks->FindAll('payment', $this->getPage(), 500);
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
     * @return GetPaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetPaymentResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}