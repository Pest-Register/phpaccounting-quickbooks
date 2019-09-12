<?php
namespace PHPAccounting\Quickbooks\Helpers;


class ErrorParsingHelper
{
    /**
     * Parses XML error object
     * @param $error
     * @return array
     */
    public static function parseError($error) {
        $errorObj = new \SimpleXMLElement($error->getResponseBody());
        $errorObj = json_decode( json_encode($errorObj) , true);
        $message = $errorObj['Fault']['Error']['Message'];
        $detail = $errorObj['Fault']['Error']['Detail'];
        return [
            'error' => [
                'status' => $error->getHttpStatusCode(),
                'message' => $message,
                'detail' => $detail
            ]
        ];
    }
}