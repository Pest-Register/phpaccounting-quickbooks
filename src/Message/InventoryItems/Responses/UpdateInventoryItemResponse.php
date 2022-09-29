<?php

namespace PHPAccounting\Quickbooks\Message\InventoryItems\Responses;

use Carbon\Carbon;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPItem;

/**
 * Get Inventory Item(s) Response
 * @package PHPAccounting\Quickbooks\Message\Invoices\Responses
 */
class UpdateInventoryItemResponse extends AbstractQuickbooksResponse
{

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
     * @param $item
     * @return array
     */
    private function parseData($item) {
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
        $newItem['selling_account_id'] = $item->IncomeAccountRef;
        $newItem['selling_tax_type_id'] = $item->SalesTaxCodeRef;
        $newItem['selling_unit_price'] = $item->UnitPrice;
        $newItem['selling_tax_inclusive'] = filter_var($item->SalesTaxIncluded, FILTER_VALIDATE_BOOLEAN);

        return $newItem;
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
            if ($item->Type !== 'Category')
            {
                $newItem = $this->parseData($item);
                $items[] = $newItem;
            }
        } else {
            foreach ($this->data as $item) {
                // Categories cannot be used in transactions, so they are ignored
                if ($item->Type !== 'Category')
                {
                    $newItem = $this->parseData($item);
                    $items[] = $newItem;
                }
            }
        }

        return $items;
    }
}
