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
class GetInventoryItemResponse extends AbstractResponse
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
                    return ErrorResponseHelper::parseErrorResponse($this->data['error']['detail']['message'], 'Inventory Item');
                }
            } elseif (array_key_exists('status', $this->data)) {
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'], 'Inventory Item');
            }
        } else {
            return ['message' => 'NULL Returned from API or End of Pagination'];
        }

        return null;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInventoryItems(){
        $items = [];
        if ($this->data instanceof IPPItem){
            $item = $this->data;
            $newItem = [];
            $newItem['accounting_id'] = $item->Id;
            $newItem['name'] = $item->Name;
            $newItem['code'] = $item->Name;
            $newItem['description'] = $item->Description;
            $newItem['type'] = $item->Type;
            $newItem['sync_token'] = $item->SyncToken;
            $newItem['is_buying'] = ($item->IncomeAccountRef ? true : false);
            $newItem['is_selling'] = ($item->ExpenseAccountRef ? true : false);
            $newItem['is_tracked'] = $item->TrackQtyOnHand;
            $newItem['buying_description'] = $item->PurchaseDesc;
            $newItem['selling_description'] = $item->Description;
            $newItem['quantity'] = $item->QtyOnHand;
            $newItem['cost_pool'] = $item->AvgCost;
            $newItem['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $item->MetaData->LastUpdatedTime)->toDateTimeString();
            if ($item->TrackQtyOnHand) {
                $newItem['buying_account_code'] = $item->COGSAccountRef;
            } else {
                $newItem['buying_account_code'] = $item->ExpenseAccountRef;
            }
            $newItem['buying_tax_type_code'] = $item->PurchaseTaxCodeRef;
            $newItem['buying_unit_price'] = $item->PurchaseCost;
            $newItem['selling_account_code'] = $item->IncomeAccountRef;
            $newItem['selling_tax_type_code'] = $item->SalesTaxCodeRef;
            $newItem['selling_unit_price'] = $item->UnitPrice;
            array_push($items, $newItem);

        } else {
            foreach ($this->data as $item) {
                $newItem = [];
                $newItem['accounting_id'] = $item->Id;
                $newItem['name'] = $item->Name;
                $newItem['code'] = $item->Name;
                $newItem['description'] = $item->Description;
                $newItem['type'] = $item->Type;
                $newItem['sync_token'] = $item->SyncToken;
                $newItem['is_buying'] = ($item->IncomeAccountRef ? true : false);
                $newItem['is_selling'] = ($item->ExpenseAccountRef ? true : false);
                $newItem['is_tracked'] = $item->TrackQtyOnHand;
                $newItem['buying_description'] = $item->PurchaseDesc;
                $newItem['selling_description'] = $item->Description;
                $newItem['quantity'] = $item->QtyOnHand;
                $newItem['cost_pool'] = $item->AvgCost;
                $newItem['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $item->MetaData->LastUpdatedTime)->toDateTimeString();
                if ($item->TrackQtyOnHand) {
                    $newItem['buying_account_code'] = $item->COGSAccountRef;
                } else {
                    $newItem['buying_account_code'] = $item->ExpenseAccountRef;
                }
                $newItem['buying_tax_type_code'] = $item->PurchaseTaxCodeRef;
                $newItem['buying_unit_price'] = $item->PurchaseCost;
                $newItem['selling_account_code'] = $item->IncomeAccountRef;
                $newItem['selling_tax_type_code'] = $item->SalesTaxCodeRef;
                $newItem['selling_unit_price'] = $item->UnitPrice;
                array_push($items, $newItem);
            }
        }

        return $items;
    }
}