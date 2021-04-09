<?php

namespace Tests\Invoices;

use Faker;
use Tests\BaseTest;

class CreateInvoiceTest extends BaseTest
{
    public function testCreateInvoice(){
        $this->setUp();
        try {
            $faker = Faker\Factory::create();
            $params = [
                'type' => 'ACCREC',
                'date' => '2021-04-21',
                'due_date' => '2021-04-22',
                'contact' => '137',
                'deposit' => 0.0,
                'invoice_data' => [
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'amount' => 130,
                        'quantity' => 1.0,
                        'unit_amount' => 130,
                        'discount_rate' => 0.0,
                        'code' => 7185,
                        'tax_type' => 15,
                        'unit' => 'QTY',
                        'tax_id' => 15,
                        'account_id' => 221,
                        'discount_amount' => 0.0,
                        'item_id' => 39
                    ],
                ],
                'total_discount' => 0.0,
                'gst_registered' => false,
                'invoice_number' => 'test invoice 69',
                'invoice_reference' => 'test invoice 69',
                'total' => 143.00,
                'deposit_amount' => NULL,
                'gst_inclusive' => 'EXCLUSIVE',
                'sync_token' => null,
                'total_tax' => 13,
                'tax_lines' => [
                    [
                        'tax_id' => 15,
                        'tax_rate_id' => 24,
                        'tax_percent' => 10.0,
                        'net_amount' => 130,
                        'percent_based' => true,
                        'total_tax' => 13,
                    ],
                ],
                'address' => [
                    'address_type' => 'BILLING',
                    'address_line_1' => '12 Rupert Street',
                    'city' => 'Collingwood',
                    'postal_code' => '3066',
                    'country' => 'Australia',
                ]
            ];

            $response = $this->gateway->createInvoice($params)->send();
            if ($response->isSuccessful()) {
                echo print_r($response->getInvoices(), true);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}