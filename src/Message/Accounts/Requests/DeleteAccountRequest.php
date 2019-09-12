<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Requests;

use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\Accounts\Responses\DeleteAccountResponse;
use QuickBooksOnline\API\Facades\Account;


/**
 * Delete Account(s)
 * @package PHPAccounting\Quickbooks\Message\Accounts\Requests
 */
class DeleteAccountRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param $value
     * @return DeleteAccountRequest
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
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('accounting_id');
        $this->issetParam('Id', 'accounting_id');
        $this->data['Active'] = false;
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteAccountResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     * @throws \Exception
     */
    public function sendData($data)
    {
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
        if (!empty($targetAccount) && sizeof($targetAccount) == 1) {
            $account = Account::update(current($targetAccount),$updateParams);
            $response = $quickbooks->Update($account);
        } else {
            return $this->createResponse([
                'status' => 'error',
                'detail' => 'Existing Account not Found'
            ]);
        }

        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }


        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return DeleteAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteAccountResponse($this, $data);
    }
}