<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 2:38 PM
 */

namespace Tests\Contacts;
use Faker;
use Tests\BaseTest;

class DeleteContactTest extends BaseTest
{
    /**
     *
     */
    public function testDeleteContact()
    {
        $this->setUp();
        try {

            $params = [
                'accounting_id' => '81',
            ];

            $response = $this->gateway->deleteContact($params)->send();
            if ($response->isSuccessful()) {
                $contacts = $response->getContacts();
                $this->assertIsArray($contacts);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}