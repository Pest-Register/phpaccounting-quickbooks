<?php


namespace Tests\Payments;


use Tests\BaseTest;

class UpdatePaymentTest extends BaseTest
{
    public function testUpdatePayment(){
        $this->setUp();
        try {

            $params = [
                'accounting_id' => '',
                'is_reconciled' => true
            ];

            $response = $this->gateway->updatePayment($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getPayments());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}