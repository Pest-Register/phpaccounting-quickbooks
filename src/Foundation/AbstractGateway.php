<?php

namespace PHPAccounting\Quickbooks\Foundation;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPAccounting\Quickbooks\Foundation\Contracts\RequestInterface;
use PHPAccounting\Quickbooks\Foundation\Traits\ParametersTrait;

/**
 * Lightweight gateway base — replaces Omnipay\Common\AbstractGateway so the
 * package can drop the omnipay/common dependency. The shape (createRequest,
 * parameter bag, optional HTTP client) intentionally mirrors what
 * laravelaccountingsync's BaseModel expects so existing callers don't break.
 */
abstract class AbstractGateway
{
    use ParametersTrait;

    protected ?ClientInterface $httpClient = null;

    /**
     * Accepts up to two ignored positional args so that
     * `Omnipay::create('\PHPAccounting\Quickbooks\Gateway')` — which calls
     * `new $class($httpClient, $httpRequest)` — continues to work without
     * touching laravelaccountingsync.
     */
    public function __construct(?ClientInterface $httpClient = null, $unused = null)
    {
        $this->httpClient = $httpClient;
    }

    abstract public function getName();

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

    public function setHttpClient(ClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    protected function createRequest(string $class, array $parameters = []): RequestInterface
    {
        $request = new $class($this->getHttpClient());
        $allParameters = array_merge($this->getParameters(), $parameters);
        $request->initialize($allParameters);

        return $request;
    }
}
