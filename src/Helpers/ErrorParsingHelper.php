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
        if (array_key_exists('Message', $errorObj['Fault']['Error'])) {
            $messageExploded = explode(';', $errorObj['Fault']['Error']['Message']);
            $errorResponse = [];

            if (array_key_exists(0, $messageExploded)) {
                $message = substr($messageExploded[0], strpos($messageExploded[0], "="));
                $errorResponse['message'] = $message;
            }

            if (array_key_exists(1, $messageExploded)) {
                $errorCode = substr($messageExploded[1], strpos($messageExploded[1], '='));
                $errorResponse['error_code'] = $errorCode;
            }
            if (array_key_exists(2, $messageExploded)) {
                $statusCode = substr($messageExploded[2], strpos($messageExploded[2], '='));
                $errorResponse['status_code'] = $statusCode;
            }

            if (array_key_exists('Detail', $errorObj['Fault']['Error'])) {
                $detail = $errorObj['Fault']['Error']['Detail'];
                $errorResponse['detail'] = $detail;
            }
        }


        return [
            'error' => [
                'status' => $error->getHttpStatusCode(),
                'detail' => $errorResponse
            ]
        ];
    }
}