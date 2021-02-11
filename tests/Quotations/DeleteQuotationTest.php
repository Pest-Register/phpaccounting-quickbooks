<?php


namespace Tests\Quotations;


use Tests\BaseTest;

class DeleteQuotationTest extends BaseTest
{
    public function testDeleteQuotations()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_id' => 196,
                'sync_token' => "2"
            ];

            $response = $this->gateway->deleteQuotation($params)->send();
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