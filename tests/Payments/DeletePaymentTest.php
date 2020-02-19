<?php


namespace Tests\Payments;


use Tests\BaseTest;

class DeletePaymentTest extends BaseTest
{
    public function testDeletePayment(){
        $this->setUp();
        try {
            $params = [
                "accounting_id" => "234",
                "sync_token" => "0"
            ];

            $response = $this->gateway->deletePayment($params)->send();
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