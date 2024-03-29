<?php

namespace Tests;

use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;


/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 14/05/2019
 * Time: 9:54 AM
 */

class GetContactTest extends BaseTest
{

    public function testGetContacts()
    {
        $this->setUp();
        try {
            $params = [
//                'search_params' => [
//                    'DisplayName' => ''
//                ],
//                'exact_search_value' => true,
                'accounting_id' => '',
                'page' => 1
            ];

            $response = $this->gateway->getContact($params)->send();
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
