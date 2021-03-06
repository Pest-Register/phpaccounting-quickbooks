<?php

namespace Tests;
use Faker;

class CreateAccountTest extends BaseTest
{
    public function testCreateAccount(){
        $this->setUp();
        try {

            $params = [
                'code' => 92,
                'name' => 'Test',
                'type' => 'Accounts Receivable',
                'tax_type' => 'INPUT',
                'tax_type_id' => 1,
            ];

            $response = $this->gateway->createAccount($params)->send();
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