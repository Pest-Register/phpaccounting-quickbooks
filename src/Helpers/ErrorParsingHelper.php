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
        $message = '';
        $detail = '';
        if (array_key_exists('Message', $errorObj['Fault']['Error'])) {
            $message = $errorObj['Fault']['Error']['Message'];
        }

        if (array_key_exists('Detail', $errorObj['Fault']['Error'])) {
            $detail = $errorObj['Fault']['Error']['Detail'];
        }

        return [
            'error' => [
                'status' => $error->getHttpStatusCode(),
                'message' => $message,
                'detail' => $detail
            ]
        ];
    }
}