<?php

namespace PHPAccounting\Quickbooks\Foundation;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPAccounting\Quickbooks\Foundation\Contracts\RequestInterface;
use PHPAccounting\Quickbooks\Foundation\Contracts\ResponseInterface;
use PHPAccounting\Quickbooks\Foundation\Exceptions\InvalidRequestException;
use PHPAccounting\Quickbooks\Foundation\Traits\ParametersTrait;

/**
 * Lightweight request base — replaces Omnipay\Common\Message\AbstractRequest.
 */
abstract class AbstractRequest implements RequestInterface
{
    use ParametersTrait;

    protected ?ClientInterface $httpClient = null;
    protected ?ResponseInterface $response = null;

    public function __construct(?ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new Client([
            'timeout' => 60,
            'http_errors' => false,
        ]);
    }

    public function setHttpClient(ClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    public function getHttpClient(): ClientInterface
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client([
                'timeout' => 60,
                'http_errors' => false,
            ]);
        }
        return $this->httpClient;
    }

    protected function validate(string ...$args): void
    {
        foreach ($args as $key) {
            $value = $this->getParameter($key);
            if ($value === null || $value === '') {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function send()
    {
        $data = $this->getData();
        return $this->sendData($data);
    }

    abstract public function getData();

    abstract public function sendData($data);
}
