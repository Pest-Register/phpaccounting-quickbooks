<?php

namespace Tests\Invoices;


use Tests\BaseTest;

class CreatePaymentTest extends BaseTest
{
    public function testCreatePayment(){
        $this->setUp();
        try {

            $params = array (
                'contact' =>
                    array (
                        'accounting_id' => '1',
                    ),
                'currency' => 'AUD',
                'currency_rate' => 1.0,
                'amount' => 300.0,
                'reference_id' => 'PR00001',
                'is_reconciled' => false,
                'invoice' =>
                    array (
                        'accounting_id' => '289',
                        'amount' => '5501.00'
                    ),
                'account' =>
                    array (
                        'accounting_id' => '106',
                    ),
                'date' => '2021-09-06',
                'sync_token' => NULL,
            );

            $response = $this->gateway->createPayment($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getPayments());
            } else {
                var_dump($response->getErrorMessage());
            }

        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}