<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 29/05/2019
 * Time: 10:47 AM
 */

namespace Tests\Invoices;


use Tests\BaseTest;

class CreateInvoiceTest extends BaseTest
{
    public function testCreateInvoice(){
        $this->setUp();
        try {

            $params = [
                'type' => 'ACCREC',
                'contact' => '39acded4-3c5d-48b1-8acf-4df92abb56a7',
                'invoice_data' => [
                    [
                        'description' => 'Consulting services as agreed (20% off standard rate)',
                        'quantity' => '10',
                        'unit_amount' => '100.00',
                        'discount' => '20'
                    ]
                ]
            ];

            $response = $this->gateway->createInvoice($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getData());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getTrace());
        }
    }
}