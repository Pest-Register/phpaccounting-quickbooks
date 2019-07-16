<?php
namespace Tests;

use Dotenv\Dotenv;
use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public $gateway;

    public function setUp()
    {
        parent::setUp();
        $dotenv = Dotenv::create(__DIR__ . '/..');
        $dotenv->load();
        $this->gateway = Omnipay::create('\PHPAccounting\Quickbooks\Gateway');
        $this->gateway->setClientID(getenv('CLIENT_ID'));
        $this->gateway->setClientSecret(getenv('CLIENT_SECRET'));
        $this->gateway->setAccessToken(getenv('ACCESS_TOKEN'));
        $this->gateway->setRefreshToken(getenv('REFRESH_TOKEN'));
        $this->gateway->setQBORealmID(getenv('QBO_REALM_ID'));
        $this->gateway->setBaseURL(getenv('BASE_URL'));
    }

}