<?php

namespace PHPAccounting\Quickbooks\Message\InventoryItems\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\InventoryItems\Responses\DeleteInventoryItemResponse;
use PHPAccounting\Quickbooks\Traits\AccountingIDRequestTrait;
use QuickBooksOnline\API\Facades\Item;

/**
 * Delete Contact(s)
 * @package PHPAccounting\Quickbooks\Message\Contacts\Requests
 */
class DeleteInventoryItemRequest extends AbstractQuickbooksRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'InventoryItem';

    /**
     * Get Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getSyncToken(){
        return $this->getParameter('sync_token');
    }

    /**
     * Set Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param string $value Account Code
     * @return DeleteInventoryItemRequest
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
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
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteInventoryItemResponse
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
            $targetItem = $quickbooks->Query("select * from Item where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'detail' => $exception->getMessage()
            ]);
        }

        try {
            if (!empty($targetItem) && sizeof($targetItem) == 1) {
                $item = Item::update(current($targetItem), $updateParams);
                $response = $quickbooks->Update($item);
            } else {
                return $this->createResponse([
                    'status' => 'error',
                    'type' => 'InvalidRequestException',
                    'detail' =>
                        [
                            'message' => 'Existing Item not found',
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
     * @return DeleteInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteInventoryItemResponse($this, $data);
    }
}
