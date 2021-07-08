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

            $params = array (
                'accounting_id' => '219',
                'address' =>
                    array (
                        'address_line_1' => ' ',
                        'city' => 'Sydney',
                        'postal_code' => NULL,
                        'state' => 'New South Wales',
                        'country' => 'Australia',
                    ),
                'type' => 'ACCREC',
                'date' => '2021-03-31',
                'due_date' => '2021-04-14',
                'contact' => '1',
                'email_status' => false,
                'amount_paid' => '0.00',
                'amount_due' => '116.00',
                'invoice_data' =>
                    array (
                        0 =>
                            array (
                                'description' => 'Entertainment',
                                'accounting_id' => NULL,
                                'amount' => 18.18,
                                'quantity' => 1.0,
                                'unit_amount' => 18.18,
                                'discount_rate' => 0.0,
                                'item_code' => 'Entertainment',
                                'item_id' => '6',
                                'unit' => 'QTY',
                                'tax_id' => '10',
                                'tax_type' => 'GST',
                                'discount_amount' => 0.0,
                                'tax_inclusive_amount' => 20.0,
                            ),
                    ),
                'total_discount' => 0.0,
                'gst_registered' => false,
                'invoice_number' => '20210331_0001',
                'invoice_reference' => '20210331_0001',
                'total' => 116.0,
                'discount_amount' => '4.00',
                'discount_rate' => 3.076662717887967,
                'deposit_amount' => '0.00',
                'gst_inclusive' => 'INCLUSIVE',
                'sync_token' => NULL,
                'total_tax' => '1.45',
                'tax_lines' =>
                    array (
                        '' =>
                            array (
                                'tax_id' => NULL,
                                'tax_rate_id' => NULL,
                                'tax_percent' => NULL,
                                'net_amount' => 100.0,
                                'percent_based' => true,
                                'total_tax' => 0,
                            ),
                        10 =>
                            array (
                                'tax_id' => '10',
                                'tax_rate_id' => '20',
                                'tax_percent' => '10',
                                'net_amount' => 14.55,
                                'percent_based' => true,
                                'total_tax' => 1.45,
                            ),
                    ),
            );

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