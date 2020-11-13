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
                'contact' => '121',
                'deposit' => 0.0,
                'invoice_data' => [
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'amount' => 494.55,
                        'quantity' => 1.0,
                        'unit_amount' => 494.55,
                        'discount_rate' => 0.0,
                        'code' => 7185,
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 15,
                        'account_id' => '112',
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 544.0,
                    ],
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'amount' => 181.82,
                        'quantity' => 1.0,
                        'unit_amount' => 181.82,
                        'discount_rate' => 0.0,
                        'code' => 7185,
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 15,
                        'account_id' => '112',
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 200.0,
                    ],
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'amount' => 613.64,
                        'quantity' => 1.0,
                        'unit_amount' => 613.64,
                        'discount_rate' => 0.0,
                        'code' => 7185,
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 15,
                        'account_id' => '112',
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 675.0,
                    ],
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'amount' => 523.64,
                        'quantity' => 1.0,
                        'unit_amount' => 523.64,
                        'discount_rate' => 0.0,
                        'code' => 7185,
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 15,
                        'account_id' => '112',
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 576.0,
                    ]
                ],
                'total_discount' => 0.0,
                'gst_registered' => false,
                'invoice_number' => '20200327_0002',
                'invoice_reference' => '20200327_0002',
                'total' => 1897.40,
                'discount_amount' => 97.60,
                'discount_rate' => 4.89223,
                'deposit_amount' => NULL,
                'gst_inclusive' => 'INCLUSIVE',
                'sync_token' => '4',
                'total_tax' => 172.49,
                'tax_lines' => [
                    [
                        'tax_id' => 15,
                        'tax_rate_id' => 24,
                        'tax_percent' => 10.0,
                        'net_amount' => 1724.91,
                        'percent_based' => true,
                        'total_tax' => 172.49,
                    ],
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