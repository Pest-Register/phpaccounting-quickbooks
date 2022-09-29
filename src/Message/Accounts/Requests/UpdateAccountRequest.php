<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Accounts\Responses\UpdateAccountResponse;
use QuickBooksOnline\API\Facades\Account;

/**
 * Update Account(s)
 * @package PHPAccounting\Quickbooks\Message\Accounts\Requests
 */
class UpdateAccountRequest extends AbstractQuickbooksRequest
{
    public string $model = 'Account';

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
     * @return UpdateAccountRequest
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
    }

    /**
     * Get Code Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getCode(){
        return $this->getParameter('code');
    }

    /**
     * Set Code Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Account Code
     * @return UpdateAccountRequest
     */
    public function setCode($value){
        return $this->setParameter('code', $value);
    }

    /**
     * Get Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getName(){
        return $this->getParameter('name');
    }

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Account Name
     * @return UpdateAccountRequest
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Get Type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getType(){
        return $this->getParameter('type');
    }

    /**
     * Set Type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Account Type
     * @return UpdateAccountRequest
     */
    public function setType($value){
        return $this->setParameter('type', $value);
    }

    /**
     * Get Description Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getDescription(){
        return $this->getParameter('description');
    }

    /**
     * Set Description Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Account Description
     * @return UpdateAccountRequest
     */
    public function setDescription($value){
        return $this->setParameter('description', $value);
    }

    /**
     * Get Tax Type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getTaxTypeID(){
        return $this->getParameter('tax_type_id');
    }

    /**
     * Set Tax Type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Account Tax Type
     * @return UpdateAccountRequest
     */
    public function setTaxTypeID($value){
        return $this->setParameter('tax_type_id', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param $value
     * @return UpdateAccountRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Set Currency Code Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Account Show Inexpense Claim
     * @return UpdateAccountRequest
     */
    public function setCurrencyCode($value){
        return $this->setParameter('currency_code', $value);
    }

    /**
     * Get Bank Account Number Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getCurrencyCode(){
        return $this->getParameter('currency_code');
    }

    /**
     * Set Sub type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Sub Type
     * @return UpdateAccountRequest
     */
    public function setSubType($value){
        return $this->setParameter('sub_type', $value);
    }

    /**
     * Get Sub Type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getSubType(){
        return $this->getParameter('sub_type');
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
        $this->issetParam('AcctNum', 'code');
        $this->issetParam('Name', 'name');
        $this->issetParam('SyncToken', 'sync_token');
        $this->issetParam('AccountType', 'type');
        $this->issetParam('AccountSubType', 'sub_type');
        $this->issetParam('Description', 'description');
        $this->issetParam('CurrencyRef', 'currency_code');

        $this->data['sparse'] = true;

        if ($this->getTaxTypeID()) {
            $this->data['TaxCodeRef'] = [
                'value' => $this->getTaxTypeID()
            ];
        }

        $this->data['sparse'] = true;
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|UpdateAccountResponse
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
            $targetAccount = $quickbooks->Query("select * from Account where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'detail' => $exception->getMessage()
            ]);
        }

        try {
            if (!empty($targetAccount) && sizeof($targetAccount) == 1) {
                $account = Account::update(current($targetAccount), $updateParams);
                $response = $quickbooks->Update($account);
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
                                'message' => 'Existing Account not found',
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
     * @return UpdateAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateAccountResponse($this, $data);
    }
}
