<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Accounts\Requests\Traits\AccountRequestTrait;
use PHPAccounting\Quickbooks\Message\Accounts\Responses\CreateAccountResponse;
use QuickBooksOnline\API\Facades\Account;


/**
 * Create Account(s)
 * @package PHPAccounting\Quickbooks\Message\Accounts\Requests
 */
class CreateAccountRequest extends AbstractQuickbooksRequest
{
    use AccountRequestTrait;

    public string $model = 'Account';

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
            $this->validate('code', 'name', 'type');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('AcctNum', 'code');
        $this->issetParam('Name', 'name');
        $this->issetParam('AccountType', 'type');
        $this->issetParam('AccountSubType', 'sub_type');
        if ($this->getTaxTypeID()) {
            $this->data['TaxCodeRef'] = [
                'value' => $this->getTaxTypeID()
            ];
        }
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
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
        $createParams = [];

        foreach ($data as $key => $value){
            $createParams[$key] = $data[$key];
        }

        try {
            $account = Account::create($createParams);
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
     * @return CreateAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateAccountResponse($this, $data);
    }
}
