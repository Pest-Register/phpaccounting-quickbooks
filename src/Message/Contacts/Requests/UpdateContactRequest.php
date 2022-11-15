<?php
namespace PHPAccounting\Quickbooks\Message\Contacts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\AddressMatchChecker;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Helpers\PhoneChecker;
use PHPAccounting\Quickbooks\Message\Accounts\Requests\UpdateAccountRequest;
use PHPAccounting\Quickbooks\Message\Contacts\Requests\Traits\ContactRequestTrait;
use PHPAccounting\Quickbooks\Message\Contacts\Responses\CreateContactResponse;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use QuickBooksOnline\API\Facades\Customer;

/**
 * Update Contact(s)
 * @package PHPAccounting\Quickbooks\Message\Contacts\Requests
 */
class UpdateContactRequest extends AbstractQuickbooksRequest
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
            $this->validate('name', 'accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('Id', 'accounting_id');
        $this->issetParam('DisplayName', 'name');
        $this->issetParam('SyncToken', 'sync_token');
        $this->issetParam('GivenName', 'first_name');
        $this->issetParam('FamilyName', 'last_name');

        $this->data['sparse'] = true;

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
     * @return CreateContactResponse
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
        $updateParams = [];

        foreach ($data as $key => $value){
            $updateParams[$key] = $data[$key];
        }
        $id = $this->getAccountingID();
        try {
            $targetCustomer = $quickbooks->Query("select * from Customer where Active = false and Id='".$id."'");
            if (!$targetCustomer) {
                $targetCustomer = $quickbooks->Query("select * from Customer where Id='".$id."'");
            }
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'detail' => $exception->getMessage()
            ]);
        }

        try {
            if (!empty($targetCustomer) && sizeof($targetCustomer) == 1) {
                $customer = Customer::update(current($targetCustomer), $updateParams);
                $response = $quickbooks->Update($customer);
            } else {
                $error = $quickbooks->getLastError();
                if ($error) {
                    $response = ErrorParsingHelper::parseError($error);
                } else {
                    return $this->createResponse([
                        'status' => 'error',
                        'type' => 'InvalidRequestException',
                        'detail' =>
                            [
                                'message' => 'Existing Customer not found',
                                'error_code' => null,
                                'status_code' => 422,
                            ],
                    ]);
                }
            }
        }
        catch (\Throwable $exception) {
            $response = ErrorParsingHelper::parseQbPackageError($exception);
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
     * @return CreateContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateContactResponse($this, $data);
    }
}
