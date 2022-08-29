<?php

namespace PHPAccounting\Quickbooks\Message\InventoryItems\Responses;

use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use QuickBooksOnline\API\Data\IPPItem;

/**
 * Get Inventory Item(s) Response
 * @package PHPAccounting\Quickbooks\Message\Invoices\Responses
 */
class UpdateInventoryItemResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if (array_key_exists('status', $this->data)) {
                if (is_array($this->data)) {
                    if ($this->data['status'] == 'error') {
                        return false;
                    }
                } else {
                    if ($this->data->status == 'error') {
                        return false;
                    }
                }
            }
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    $detail = $this->data['error']['detail'] ?? [];
                    return ErrorResponseHelper::parseErrorResponse(
                        $detail['message'] ?: null,
                        $this->data['error']['status'],
                        $detail['error_code'] ?: null,
                        $detail['status_code'] ?: null,
                        $detail['detail'] ?: null,
                        'Inventory Item');
                }
            } elseif (array_key_exists('status', $this->data)) {
                $detail = $this->data['detail'] ?? [];
                return ErrorResponseHelper::parseErrorResponse(
                    $detail['message'] ?: null,
                    $this->data['status'],
                    $detail['error_code'] ?: null,
                    $detail['status_code'] ?: null,
                    $detail['detail'] ?: null,
                    'Inventory Item');
            }
        } else {
            return [
                'message' => 'NULL Returned from API or End of Pagination',
                'exception' =>'NULL Returned from API or End of Pagination',
                'error_code' => null,
                'status_code' => null,
                'detail' => null
            ];
        }

        return null;
    }

    /**
     * @param $data
     * @return string
     */
    private function parseType($data) {
        switch ($data) {
            case 'Inventory':
            case 'NonInventory':
                return 'PRODUCT';
            case 'Service':
                return 'SERVICE';
            default:
                return 'UNSUPPORTED';
        }
    }

    /**
     * Return all Inventory with Generic Schema Variable Assignment
     * @return array
     */
    public function getInventoryItems(){
        $items = [];
        if ($this->data instanceof IPPItem){
            $item = $this->data;
            // Categories cannot be used in transactions, so they are ignored
            $newItem = [];
            $newItem['accounting_id'] = $item->Id;
            $newItem['name'] = $item->Name;
            $newItem['code'] = $item->Sku;
            $newItem['description'] = $item->Description;
            $newItem['type'] = $this->parseType($item->Type);
            $newItem['sync_token'] = $item->SyncToken;
            $newItem['is_selling'] = ($item->IncomeAccountRef ? true : false);
            $newItem['is_buying'] = ($item->ExpenseAccountRef ? true : false);
            $newItem['is_tracked'] = filter_var($item->TrackQtyOnHand, FILTER_VALIDATE_BOOLEAN);
            $newItem['buying_description'] = $item->PurchaseDesc;
            $newItem['selling_description'] = $item->Description;
            $newItem['quantity'] = $item->QtyOnHand;
            $newItem['cost_pool'] = $item->AvgCost;
            if ($item->MetaData->LastUpdatedTime) {
                $updatedAt = Carbon::parse($item->MetaData->LastUpdatedTime);
                $updatedAt->setTimezone('UTC');
                $newItem['updated_at'] = $updatedAt->toDateTimeString();
            }
            if ($item->TrackQtyOnHand) {
                $newItem['buying_account_code'] = $item->COGSAccountRef;
            } else {
                $newItem['buying_account_code'] = $item->ExpenseAccountRef;
            }
            $newItem['buying_tax_type_id'] = $item->PurchaseTaxCodeRef;
            $newItem['buying_unit_price'] = $item->PurchaseCost;
            $newItem['buying_tax_inclusive'] = filter_var($item->PurchaseTaxIncluded, FILTER_VALIDATE_BOOLEAN);
            $newItem['selling_account_code'] = $item->IncomeAccountRef;
            $newItem['selling_tax_type_id'] = $item->SalesTaxCodeRef;
            $newItem['selling_unit_price'] = $item->UnitPrice;
            $newItem['selling_tax_inclusive'] = filter_var($item->SalesTaxIncluded, FILTER_VALIDATE_BOOLEAN);
            array_push($items, $newItem);
        } else {
            foreach ($this->data as $item) {
                $newItem = [];
                $newItem['accounting_id'] = $item->Id;
                $newItem['name'] = $item->Name;
                $newItem['code'] = $item->Sku;
                $newItem['description'] = $item->Description;
                $newItem['type'] = $this->parseType($item->Type);
                $newItem['sync_token'] = $item->SyncToken;
                $newItem['is_selling'] = ($item->IncomeAccountRef ? true : false);
                $newItem['is_buying'] = ($item->ExpenseAccountRef ? true : false);
                $newItem['is_tracked'] = filter_var($item->TrackQtyOnHand, FILTER_VALIDATE_BOOLEAN);
                $newItem['buying_description'] = $item->PurchaseDesc;
                $newItem['selling_description'] = $item->Description;
                $newItem['quantity'] = $item->QtyOnHand;
                $newItem['cost_pool'] = $item->AvgCost;
                if ($item->MetaData->LastUpdatedTime) {
                    $updatedAt = Carbon::parse($item->MetaData->LastUpdatedTime);
                    $updatedAt->setTimezone('UTC');
                    $newItem['updated_at'] = $updatedAt->toDateTimeString();
                }
                if ($item->TrackQtyOnHand) {
                    $newItem['buying_account_code'] = $item->COGSAccountRef;
                } else {
                    $newItem['buying_account_code'] = $item->ExpenseAccountRef;
                }
                $newItem['buying_tax_type_id'] = $item->PurchaseTaxCodeRef;
                $newItem['buying_unit_price'] = $item->PurchaseCost;
                $newItem['buying_tax_inclusive'] = filter_var($item->PurchaseTaxIncluded, FILTER_VALIDATE_BOOLEAN);
                $newItem['selling_account_code'] = $item->IncomeAccountRef;
                $newItem['selling_tax_type_id'] = $item->SalesTaxCodeRef;
                $newItem['selling_unit_price'] = $item->UnitPrice;
                $newItem['selling_tax_inclusive'] = filter_var($item->SalesTaxIncluded, FILTER_VALIDATE_BOOLEAN);
                array_push($items, $newItem);
            }
        }

        return $items;
    }
}
