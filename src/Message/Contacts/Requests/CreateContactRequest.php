<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Contacts\Requests\Traits\ContactRequestTrait;
use PHPAccounting\Quickbooks\Message\Contacts\Responses\CreateContactResponse;
use QuickBooksOnline\API\Facades\Customer;

/**
 * Create Contact(s)
 * @package PHPAccounting\Quickbooks\Message\Contacts\Requests
 */
class CreateContactRequest extends AbstractQuickbooksRequest
{
    use ContactRequestTrait;

    public string $model = 'Contact';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        try {
            $this->validate('name');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('DisplayName', 'name');
        $this->issetParam('GivenName', 'first_name');
        $this->issetParam('FamilyName', 'last_name');

        if ($this->getStatus()) {
            if ($this->getStatus() == 'ACTIVE') {
                $this->data['Active'] = true;
            } elseif ($this->getStatus() == 'INACTIVE') {
                $this->data['Active'] = false;
            }
        }

        if ($this->getEmailAddress()) {
            $this->data['PrimaryEmailAddr'] = [
                'Address' => $this->getEmailAddress()
            ];
        }

        if ($this->getWebsite()) {
            $this->data['WebAddr'] = [
                'URI' => $this->getWebsite()
            ];
        }

        if ($this->getPhones()) {
            $this->data = $this->getPhoneData($this->getPhones(), $this->data);
        }

        if ($this->getAddresses()) {
            $this->data = $this->getAddressData($this->getAddresses(), $this->data);
        }
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateContactResponse
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
        $createParams = [];

        foreach ($data as $key => $value){
            $createParams[$key] = $data[$key];
        }

        try {
            $account = Customer::create($createParams);
            $response = $quickbooks->Add($account);
            $error = $quickbooks->getLastError();
            if ($error) {
                $response = ErrorParsingHelper::parseError($error);
            }
        }
        catch (\Throwable $exception) {
            $response = ErrorParsingHelper::parseQbPackageError($exception);
        }

        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return CreateContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateContactResponse($this, $data);
    }

}
