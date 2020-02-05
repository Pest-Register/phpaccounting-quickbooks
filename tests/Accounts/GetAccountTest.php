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

class GetAccountTest extends BaseTest
{

    public function testGetAccounts()
    {
        $this->setUp();
        $params = [
            'accountingID' => "",
            'page' => 110
        ];
        try {
            $response = $this->gateway->getAccount($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getAccounts());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
