<?php
namespace Tests;
use Faker;
use Tests\BaseTest;

class DeleteAccountTest extends BaseTest
{
    public function testDeleteAccount(){
        $this->setUp();
        try {

            $params = [
                'accounting_id' => 118
            ];

            $response = $this->gateway->deleteAccount($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getAccounts());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}