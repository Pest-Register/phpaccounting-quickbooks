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
                'date' => '2020-03-20',
                'due_date' => '2020-04-03',
                'contact' => '1',
                'deposit' => 0.0,
                'invoice_data' => [
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'amount' => 200.0,
                        'quantity' => 1.0,
                        'unit_amount' => '200.0000',
                        'discount_rate' => 0.0,
                        'code' => 6614,
                        'tax_type' => 'Input tax',
                        'unit' => 'QTY',
                        'tax_id' => 6,
                        'account_id' => '1',
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 200.0,
                    ],
                    [
                        'description' => 'Test 2',
                        'accounting_id' => '',
                        'amount' => 500.0,
                        'quantity' => 1.0,
                        'unit_amount' => '500.0000',
                        'discount_rate' => 0.0,
                        'code' => 6527,
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 10,
                        'account_id' => '112',
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 500.0,
                    ],
                    [
                        'description' => 'Test 3',
                        'accounting_id' => '',
                        'amount' => 200.0,
                        'quantity' => 1.0,
                        'unit_amount' => '200.0000',
                        'discount_rate' => 0.0,
                        'code' => 6527,
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 10,
                        'account_id' => '112',
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 200.0,
                    ],
                    [
                        'description' => 'Test 4',
                        'accounting_id' => '',
                        'amount' => 786.0,
                        'quantity' => 1.0,
                        'unit_amount' => '786.0000',
                        'discount_rate' => 0.0,
                        'code' => 6527,
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 10,
                        'account_id' => '112',
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 786.0,
                    ]
                ],
                'total_discount' => 0.0,
                'gst_registered' => false,
                'invoice_number' => '0200320_00068',
                'invoice_reference' => '20200320_00068',
                'total' => 1686.0,
                'discount_amount' => 0.0,
                'discount_rate' => 0.0,
                'deposit_amount' => NULL,
                'gst_inclusive' => 'INCLUSIVE',
                'sync_token' => '2',
                'total_tax' => 135.09,
                'tax_lines' => [
                    [
                        'tax_id' => '6',
                        'tax_rate_id' => 6,
                        'tax_percent' => 0.0,
                        'net_amount' => 200.0,
                        'percent_based' => true,
                        'total_tax' => 0.0,
                    ],
                    [
                        'tax_id' => '10',
                        'tax_rate_id' => 20,
                        'tax_percent' => 10.0,
                        'net_amount' => 500.0,
                        'percent_based' => true,
                        'total_tax' => 135.09,
                    ]
                ],
                'address' => [
                    'address_line_1' => ' ',
                    'city' => NULL,
                    'postal_code' => NULL,
                    'state' => NULL,
                    'country' => 'Australia',
                ]
            ];

            $response = $this->gateway->createInvoice($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getInvoices());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}