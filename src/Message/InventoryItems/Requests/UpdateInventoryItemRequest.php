<?php

namespace PHPAccounting\Quickbooks\Message\InventoryItems\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\InventoryItems\Requests\Traits\InventoryItemRequestTrait;
use PHPAccounting\Quickbooks\Message\InventoryItems\Responses\UpdateInventoryItemResponse;
use QuickBooksOnline\API\Facades\Item;

/**
 * Create Inventory Item
 * @package PHPAccounting\Quickbooks\Message\InventoryItems\Requests
 */
class UpdateInventoryItemRequest extends AbstractQuickbooksRequest
{
    use InventoryItemRequestTrait;

    public string $model = 'InventoryItem';

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

        $datetime = new \DateTime('NOW');
        $this->issetParam('Name', 'name');
        $this->issetParam('Description', 'description');
        $this->issetParam('PurchaseDesc', 'buying_description');
        $this->issetParam('Sku', 'code');
        $this->issetParam('TrackQtyOnHand', 'is_tracked');
        $this->issetParam('QtyOnHand', 'quantity');
        if ($this->getInventoryAccountCode()) {
            $this->data['COGSAccountRef'] = [
                'value' => $this->getInventoryAccountCode()
            ];
        }

        // Set item type
        if ($this->getType()) {
            $itemType = $this->getType();
            if ($itemType === 'PRODUCT') {
                if ($this->getIsTracked()) {
                    $this->data['Type'] = 'Inventory';
                } else {
                    $this->data['Type'] = 'NonInventory';
                }
            }
            else if ($itemType === 'SERVICE') {
                $this->data['Type'] = 'Service';
            }
        }

        $buyingDetails = $this->getBuyingDetails();
        $salesDetails = $this->getSalesDetails();
        $assetDetails = $this->getAssetDetails();

        if ($buyingDetails) {
            if (array_key_exists('tracked_buying_account_code',$buyingDetails)) {
                $this->data['COGSAccountCode'] = [
                    'value' => $buyingDetails['tracked_buying_account_code']
                ];
            } else {
                $this->data['ExpenseAccountRef'] = [
                    'value' => $buyingDetails['buying_account_code']
                ];
            }
            if (array_key_exists('buying_unit_price', $buyingDetails)) {
                $this->data['PurchaseCost'] = $buyingDetails['buying_unit_price'];
            }

            if (array_key_exists('buying_tax_inclusive', $buyingDetails)) {
                $this->data['PurchaseTaxIncluded'] = $buyingDetails['buying_tax_inclusive'];
            }

            if (array_key_exists('buying_tax_type_id', $buyingDetails)) {
                $this->data['PurchaseTaxCodeRef'] = $buyingDetails['buying_tax_type_id'];
            }
        }

        if ($salesDetails) {
            if (array_key_exists('selling_account_code',$salesDetails)) {
                $this->data['IncomeAccountRef'] = [
                    'value' => $salesDetails['selling_account_code']
                ];
            }
            if (array_key_exists('selling_unit_price', $salesDetails)) {
                $this->data['UnitPrice'] = $salesDetails['selling_unit_price'];
            }

            if (array_key_exists('selling_tax_inclusive', $salesDetails)) {
                $this->data['SalesTaxIncluded'] = $salesDetails['selling_tax_inclusive'];
            }

            if (array_key_exists('selling_tax_type_id', $salesDetails)) {
                $this->data['SalesTaxCodeRef'] = $salesDetails['selling_tax_type_id'];
            }
        }

        if ($assetDetails) {
            if (array_key_exists('asset_account_code', $assetDetails)) {
                $this->data['AssetAccountRef'] = [
                    'value' => $assetDetails['asset_account_code']
                ];
            }
        }


        $this->data['Active'] = true;
        $this->data['InvStartDate'] = $datetime;
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return UpdateInventoryItemResponse
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
            $targetItem = $quickbooks->Query("select * from Item where Active = false and Id='".$id."'");
            if (!$targetItem) {
                $targetItem = $quickbooks->Query("select * from Item where Id='".$id."'");
            }
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'type' => 'InvalidRequestException',
                'detail' =>
                    [
                        'message' => $exception->getMessage(),
                        'error_code' => null,
                        'status_code' => 422,
                    ],
            ]);
        }

        try {
            if (!empty($targetItem) && sizeof($targetItem) == 1) {
                $item = Item::update(current($targetItem), $updateParams);
                $response = $quickbooks->Update($item);
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
                                'message' => 'Existing Item not found',
                                'error_code' => null,
                                'status_code' => 422,
                            ],
                    ]);
                }
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
     * @return UpdateInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateInventoryItemResponse($this, $data);
    }
}
