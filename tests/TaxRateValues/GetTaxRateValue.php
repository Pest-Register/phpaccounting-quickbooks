<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 14/10/2019
 * Time: 3:10 PM
 */

namespace Tests\TaxRateValues;


use Tests\BaseTest;

class GetTaxRateValue extends BaseTest
{

    public function testGetTaxRateValues()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_id' => 10,
                'page' => 1
            ];

            $response = $this->gateway->getTaxRateValue($params)->send();
            if ($response->isSuccessful()) {
                var_dump(json_encode($response->getTaxRateValues()));
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}