<?php

namespace PHPAccounting\Quickbooks\Message\InventoryItems\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\InventoryItems\Responses\CreateInventoryItemResponse;
use QuickBooksOnline\API\Facades\Item;

/**
 * Create Inventory Item
 * @package PHPAccounting\Quickbooks\Message\InventoryItems\Requests
 */
class CreateInventoryItemRequest extends AbstractQuickbooksRequest
{
    public string $model = 'InventoryItem';

    /**
     * Get Quantity Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getQuantity(){
        return $this->getParameter('quantity');
    }

    /**
     * Set Quantity Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param string $value Account Code
     * @return CreateInventoryItemRequest
     */
    public function setQuantity($value){
        return $this->setParameter('quantity', $value);
    }
    /**
     * Get Code Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getCode(){
        return $this->getParameter('code');
    }

    /**
     * Set Code Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param string $value Account Code
     * @return CreateInventoryItemRequest
     */
    public function setCode($value){
        return $this->setParameter('code', $value);
    }

    /**
     * Get Inventory Asset AccountCode Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getInventoryAccountCode() {
        return $this->getParameter('inventory_account_code');
    }

    /**
     * Set Inventory Asset AccountCode Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setInventoryAccountCode($value) {
        return $this->setParameter('inventory_account_code', $value);
    }

    /**
     * Get Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getName() {
        return $this->getParameter('name');
    }

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setName($value) {
        return $this->setParameter('name', $value);
    }
    /**
     * Get Is Tracked Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getIsTracked() {
        return $this->getParameter('is_tracked');
    }

    /**
     * Set Is Buying Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setIsTracked($value) {
        return $this->setParameter('is_tracked', $value);
    }
    /**
     * Get Is Buying Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getIsBuying() {
        return $this->getParameter('is_buying');
    }

    /**
     * Set Is Buying Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setIsBuying($value) {
        return $this->setParameter('is_buying', $value);
    }

    /**
     * Get Is Buying Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getIsSelling() {
        return $this->getParameter('is_selling');
    }

    /**
     * Set Is Selling Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setIsSelling($value) {
        return $this->setParameter('is_selling', $value);
    }

    /**
     * Get Description Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getDescription() {
        return $this->getParameter('description');
    }

    /**
     * Set Description Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setDescription($value) {
        return $this->setParameter('description', $value);
    }

    /**
     * Get Buying Description Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getBuyingDescription() {
        return $this->getParameter('buying_description');
    }

    /**
     * Set Buying Description Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setBuyingDescription($value) {
        return $this->setParameter('buying_description', $value);
    }

    /**
     * Get Buying Details Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getBuyingDetails() {
        return $this->getParameter('buying_details');
    }

    /**
     * Set Type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setType($value) {
        return $this->setParameter('type', $value);
    }

    /**
     * Get Type Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getType() {
        return $this->getParameter('type');
    }

    /**
     * Set Buying Details Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setBuyingDetails($value) {
        return $this->setParameter('buying_details', $value);
    }
    /**
     * Get Asset Details Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getAssetDetails() {
        return $this->getParameter('asset_details');
    }

    /**
     * Set Asset Details Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setAssetDetails($value) {
        return $this->setParameter('asset_details', $value);
    }

    /**
     * Get Sales Details Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @return mixed
     */
    public function getSalesDetails() {
        return $this->getParameter('sales_details');
    }

    /**
     * Set Sales Details Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/item
     * @param $value
     * @return mixed
     */
    public function setSalesDetails($value) {
        return $this->setParameter('sales_details', $value);
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
     * @return CreateInventoryItemResponse
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
            $item = Item::create($createParams);
            $response = $quickbooks->Add($item);
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
     * @return CreateInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateInventoryItemResponse($this, $data);
    }
}
