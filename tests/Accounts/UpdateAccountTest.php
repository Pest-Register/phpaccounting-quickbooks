<?php

namespace Tests;

use Faker;
use Omnipay\Omnipay;

class UpdateAccountTest extends BaseTest
{
    public function testUpdateAccount(){
        $this->setUp();
        try {

            $params = [
                'accounting_id' => '92',
                'code' => 999,
                'name' => 'Test 1',
                'type' => 'Accounts Receivable',
                'tax_type' => 'INPUT',
                'tax_type_id' => 1,
                'description' => 'Test Description 5'
            ];

            $response = $this->gateway->updateAccount($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getAccounts());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}