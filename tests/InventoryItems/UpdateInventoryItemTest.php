<?php


namespace Tests\InventoryItems;


use Tests\BaseTest;

class UpdateInventoryItemTest extends BaseTest
{
    public function testUpdateInventoryItem(){
        $this->setUp();
        try {

            $params = [
                'accounting_id' => 30,
                'name' => 'Development Operations 2',
                'description' => 'Development Operations 2',
                'buying_description' => 'Development Operations',
                'is_tracked' => true,
                'purchase_details' => [
                    'buying_unit_price' => 100,
                    'buying_account_code' => 88,
                    'buying_tax_type_code' => 'OUTPUT'
                ],
                'sales_details' => [
                    'selling_unit_price' => 150,
                    'selling_account_code' => 81,
                    'selling_tax_type_code' => 'OUTPUT'
                ],
                'asset_details' => [
                    'asset_account_code' => 32
                ],
                'type' => 'Inventory',
                'quantity' => 10
            ];

            $response = $this->gateway->updateInventoryItem($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getInventoryItems());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            var_dump($exception->getTrace());
        }
    }
}