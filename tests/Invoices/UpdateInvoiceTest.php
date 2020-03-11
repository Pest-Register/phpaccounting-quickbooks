<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 6:21 PM
 */

namespace Tests\Invoices;


use Tests\BaseTest;
use Faker;
class UpdateInvoiceTest extends BaseTest
{
    public function testUpdateInvoices()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [

                'accounting_id' => 128,
                'sync_token' => 2,
                'status' => 'PAID',
                'type' => 'ACCREC',
                'contact' => [
                    'accounting_id' => 3
                ],
                'invoice_data' => [
                    [
                        'description' => 'consulting for upcoming company event',
                        'line_amount' => 800,
                        'accounting_id' => 1,
                        'amount' => 800,
                        'quantity' => 4,
                        'unit_amount' => 200,
                        'account_id' => 1,
                        'item_id' => 1,
                        'tax_amount' => 800,
                        'tax_type' => 10
                    ]
                ]
            ];

            $response = $this->gateway->updateInvoice($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getInvoices());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}