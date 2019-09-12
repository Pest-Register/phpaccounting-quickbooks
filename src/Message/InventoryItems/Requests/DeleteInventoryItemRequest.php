<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Requests;

use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\InventoryItems\Responses\DeleteInventoryItemResponse;
use PHPAccounting\Quickbooks\Message\InventoryItems\Responses\GetInventoryItemResponse;
use QuickBooksOnline\API\Facades\Item;

/**
 * Delete Contact(s)
 * @package PHPAccounting\Quickbooks\Message\Contacts\Requests
 */
class DeleteInventoryItemRequest extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param $value
     * @return DeleteInventoryItemRequest
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
        $this->validate('accounting_id');
        $this->issetParam('Id', 'accounting_id');
        $this->data['Active'] = false;
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteInventoryItemResponse
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
            $targetItem = $quickbooks->Query("select * from Item where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'detail' => $exception->getMessage()
            ]);
        }
        if (!empty($targetItem) && sizeof($targetItem) == 1) {
            $item = Item::update(current($targetItem),$updateParams);
            $response = $quickbooks->Update($item);
        } else {
            return $this->createResponse([
                'status' => 'error',
                'detail' => 'Existing Item not Found'
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
     * @return GetInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetInventoryItemResponse($this, $data);
    }
}