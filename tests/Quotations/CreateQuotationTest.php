<?php


namespace Tests\Quotations;


use Tests\BaseTest;

class CreateQuotationTest extends BaseTest
{
    public function testCreateQuotation(){
        $this->setUp();
        try {
            $params = [
                'date' => '2020-03-20',
                'expiry_date' => '2020-04-03',
                'contact' => '1',
                'quotation_data' => [
                    [
                        'description' => 'Employee training off site',
                        'accounting_id' => '',
                        'amount' => 1500.00,
                        'quantity' => 1.0,
                        'unit_amount' => 1500,
                        'discount_rate' => 0.0,
                        'code' => 7185,
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 10,
                        'account_id' => 1,
                        'discount_amount' => 0.0,
                        'tax_inclusive_amount' => 1500,
                    ],
                ],
                'total_discount' => 0.0,
                'gst_registered' => false,
                'quotation_number' => '20200327_0021',
                'quotation_reference' => '20200327_0021',
                'total' => 1500.00,
                'discount_amount' => 0,
                'discount_rate' => 0,
                'gst_inclusive' => 'INCLUSIVE',
                'sync_token' => '4',
                'total_tax' => 150.00,
                'tax_lines' => [
                    [
                        'tax_id' => 10,
                        'tax_rate_id' => 20,
                        'tax_percent' => 10.0,
                        'net_amount' => 1500.00,
                        'percent_based' => true,
                        'total_tax' => 150.00,
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

            $response = $this->gateway->createQuotation($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getQuotations());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}