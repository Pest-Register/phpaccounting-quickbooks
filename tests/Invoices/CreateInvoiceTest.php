<?php

namespace Tests\Invoices;


use Tests\BaseTest;

class CreateInvoiceTest extends BaseTest
{
    public function testCreateInvoice(){
        $this->setUp();
        try {

            $params = [
                'type' => 'ACCREC',
                'date' => '2019-01-27',
                'due_date' => '2019-01-28',
                'contact' => '23',
                'invoice_reference' => '1234',
                'invoice_data' => [
                    [
                        'description' => 'Consulting services as agreed (20% off standard rate)',
                        'quantity' => '10',
                        'unit_amount' => '100.00',
                        'discount_rate' => '20',
                        'amount' => 1000,
                        'code' => 200,
                        'tax_id' => 10,
                        'account_id' => 10,
//                        'item_id' => 15
                    ]
                ]
            ];

            $response = $this->gateway->createInvoice($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getInvoices());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}