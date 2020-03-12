<?php

namespace Tests;

use Faker;
use Omnipay\Omnipay;

class UpdateContactTest extends BaseTest
{
    public function testUpdateContacts()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'accounting_id' => '72',
                'name' => 'Bob Down',
                'website' => 'https://www.bobdown.com',
                'sync_token' => 6,
                'addresses' => [
                    [
                        'type' => 'PRIMARY',
                        'address_line_1' => '454 Collins Street',
                        'city' => 'Melbourne',
                        'postal_code' => '3000',
                        'state' => 'Victoria',
                        'country' => 'Australia'
                    ]
                ]
            ];

            $response = $this->gateway->updateContact($params)->send();
            if ($response->isSuccessful()) {
                $contacts = $response->getContacts();
                var_dump($contacts);
                $this->assertIsArray($contacts);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}