<?php

namespace PHPAccounting\Quickbooks\Foundation\Contracts;

interface ResponseInterface
{
    public function getRequest(): RequestInterface;

    public function isSuccessful(): bool;

    public function getData();
}
