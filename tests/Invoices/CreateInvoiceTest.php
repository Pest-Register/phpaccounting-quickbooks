<?php

namespace Tests\Invoices;

use Faker;
use Tests\BaseTest;

class CreateInvoiceTest extends BaseTest
{
    public function testCreateInvoice(){
        $this->setUp();
        try {
            $faker = Faker\Factory::create();
            $params = [
                'address' => [],
                'type' => 'ACCREC',
                'date' => '2021-07-15',
                'due_date' => '2021-07-29',
                'contact' => '4',
                'email_status' => NULL,
                'amount_paid' => '0.00',
                'amount_due' => '1379.10',
                'invoice_data' =>
                    array (
                        0 =>
                            array (
                                'description' => NULL,
                                'accounting_id' => NULL,
                                'amount' => 790.91,
                                'quantity' => 2.0,
                                'unit_amount' => 395.455,
                                'unit' => 'HRS',
                                'tax_id' => '10',
                                'tax_type' => 'GST',
                                'account_id' => '81',
                                'code' => '81',
                                'tax_inclusive_amount' => 870.0,
                            ),
                        1 =>
                            array (
                                'description' => NULL,
                                'accounting_id' => NULL,
                                'amount' => 462.82,
                                'quantity' => 2.0,
                                'unit_amount' => 231.41,
                                'unit' => 'HRS',
                                'tax_id' => '10',
                                'tax_type' => 'GST',
                                'account_id' => '81',
                                'code' => '81',
                                'tax_inclusive_amount' => 509.1,
                            ),
                    ),
                'total_discount' => '0.00',
                'gst_registered' => false,
                'invoice_number' => 'NODISCOUNT01',
                'invoice_reference' => 'NODISCOUNT01',
                'total' => 1379.1,
                'discount_amount' => '0.00',
                'discount_rate' => '0.0000',
                'deposit_amount' => '0.00',
                'gst_inclusive' => 'INCLUSIVE',
                'sync_token' => NULL,
                'total_tax' => '125.37',
                'tax_lines' =>
                    array (
                        10 =>
                            array (
                                'total_tax' => 125.37,
                                'tax_percent' => 10,
                                'net_amount' => 1253.73,
                                'tax_rate_id' => '20',
                            ),
                    ),
                'sub_total_before_tax' => '1253.7300000000',
                'sub_total_after_tax' => '1379.1000000000',
            ];

            $response = $this->gateway->createInvoice($params)->send();
            if ($response->isSuccessful()) {
                echo print_r($response->getInvoices(), true);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}