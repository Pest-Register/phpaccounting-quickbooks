<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Accounts\Responses\DeleteAccountResponse;
use PHPAccounting\Quickbooks\Traits\AccountingIDRequestTrait;
use QuickBooksOnline\API\Facades\Account;


/**
 * Delete Account(s)
 * @package PHPAccounting\Quickbooks\Message\Accounts\Requests
 */
class DeleteAccountRequest extends AbstractQuickbooksRequest
{
    use AccountingIDRequestTrait;

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
            $this->validate('accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }
        $this->issetParam('Id', 'accounting_id');
        $this->data['Active'] = false;
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteAccountResponse
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
            $targetAccount = $quickbooks->Query("select * from Account where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'detail' => $exception->getMessage()
            ]);
        };

        try {
            if (!empty($targetAccount) && sizeof($targetAccount) == 1) {
                $account = Account::update(current($targetAccount), $updateParams);
                $response = $quickbooks->Update($account);
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
     * @return DeleteAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteAccountResponse($this, $data);
    }
}
