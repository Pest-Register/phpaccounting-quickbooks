<?php

namespace Tests;
use Faker;
class CreateContactTest extends BaseTest
{
    public function testCreateContacts()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'name' => 'Dylan Aird',
                'website' => 'https://www.bobdown.com',
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
            $response = $this->gateway->createContact($params)->send();
            if ($response->isSuccessful()) {
                $contacts = $response->getContacts();
                var_dump($contacts);
                $this->assertIsArray($contacts);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            var_dump($exception->getLine());
            var_dump($exception->getTrace());
        }
    }
}