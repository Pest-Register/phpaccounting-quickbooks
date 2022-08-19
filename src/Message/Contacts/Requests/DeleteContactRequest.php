<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\Contacts\Responses\DeleteContactResponse;
use PHPAccounting\Quickbooks\Message\Contacts\Responses\GetContactResponse;
use QuickBooksOnline\API\Facades\Customer;

/**
 * Delete Contact(s)
 * @package PHPAccounting\Quickbooks\Message\Contacts\Requests
 */
class DeleteContactRequest extends AbstractRequest
{
    /**
     * Get Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getSyncToken(){
        return $this->getParameter('sync_token');
    }

    /**
     * Set Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Account Code
     * @return DeleteContactRequest
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param $value
     * @return DeleteContactRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

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
            $this->validate('accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }
        $this->issetParam('Id', 'accounting_id');
        $this->issetParam('SyncToken', 'sync_token');
        $this->data['Active'] = false;
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteContactResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = [
                'status' => 'error',
                'type' => 'InvalidRequestException',
                'detail' =>
                    [
                        'message' => $data->getMessage(),
                        'error_code' => $data->getCode(),
                        'status_code' => 422,
                    ],
            ];
            return $this->createResponse($response);
        }

        $quickbooks = $this->createQuickbooksDataService();
        $updateParams = [];

        foreach ($data as $key => $value){
            $updateParams[$key] = $data[$key];
        }
        $id = $this->getAccountingID();
        try {
            $targetCustomer = $quickbooks->Query("select * from Customer where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'detail' => $exception->getMessage()
            ]);
        }
        if (!empty($targetCustomer) && sizeof($targetCustomer) == 1) {
            $customer = Customer::update(current($targetCustomer),$updateParams);
            $response = $quickbooks->Update($customer);
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

        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }

        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return DeleteContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteContactResponse($this, $data);
    }
}