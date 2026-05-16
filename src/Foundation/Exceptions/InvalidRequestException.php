<?php

namespace PHPAccounting\Quickbooks\Foundation\Exceptions;

use Exception;

class InvalidRequestException extends Exception
{
    public function __construct(string $message = "Invalid request", int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
