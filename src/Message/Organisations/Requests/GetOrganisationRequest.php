<?php
namespace PHPAccounting\Quickbooks\Message\Organisations\Requests;


use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Organisations\Responses\GetOrganisationResponse;
use QuickBooksOnline\API\Exception\IdsException;

class GetOrganisationRequest extends AbstractQuickbooksRequest
{
    public string $model = 'Organisation';

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetOrganisationResponse
     * @throws IdsException
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            return $this->createResponse(
                $this->handleRequestException($data, 'InvalidRequestException')
            );
        }
        $quickbooks = $this->createQuickbooksDataService();
        $quickbooks->throwExceptionOnError(true);

        $response = [
            $quickbooks->getCompanyInfo(),
            $quickbooks->getCompanyPreferences()
        ];

        $error = $quickbooks->getLastError();
        if ($error) {
            $response = [
                'status' => $error->getHttpStatusCode(),
                'detail' => $error->getResponseBody()
            ];
        }

        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return GetOrganisationResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetOrganisationResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}