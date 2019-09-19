<?php

namespace Tests\Invoices;


use Tests\BaseTest;

class CreatePaymentTest extends BaseTest
{
    public function testCreatePayment(){
        $this->setUp();
        try {

            $params = [
                'currency' => 'AUD',
                'currency_rate' => 1.0,
                'amount' => 7150.00,
                'reference_id' => 'Test Description',
                'is_reconciled' => true,
                'date' => '2019-27-06',
                'invoice' => [
                    'accounting_id' => '127',
                    'amount' => 7150.00
                ],
                'contact' => [
                    'accounting_id' => 3
                ]
            ];

            $response = $this->gateway->createPayment($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getPayments());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}