<?php


namespace Tests\InventoryItems;

use Tests\BaseTest;

class CreateInventoryItemTest extends BaseTest
{
    public function testCreateInventoryItem(){
        $this->setUp();
        try {

            $params = [
                'name' => 'Development Operations 3',
                'description' => 'Development Operations 3',
                'selling_description' => 'Development Operations',
                'is_selling' => true,
                'is_tracked' => false,
                'sales_details' => [
                    'selling_unit_price' => 150,
                    'selling_account_code' => '81',
                    'selling_tax_type_id' => '10',
                    'selling_tax_inclusive' => true
                ],
                'type' => 'NonInventory',
            ];

            $response = $this->gateway->createInventoryItem($params)->send();
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