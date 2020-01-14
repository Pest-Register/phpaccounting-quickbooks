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
        if (array_key_exists('Message', $errorObj['Fault']['Error'])) {
            $messageExploded = explode(';', $errorObj['Fault']['Error']['Message']);
            $message = '';
            $errorCode = '';
            $statusCode = '';

            if (array_key_exists(0, $messageExploded)) {
                $message = substr($messageExploded[0], strpos($messageExploded[0], "=") + 1);
            }

            if (array_key_exists(1, $messageExploded)) {
                $errorCode = substr($messageExploded[1], strpos($messageExploded[1], '=') + 1);
            }
            if (array_key_exists(2, $messageExploded)) {
                $statusCode = substr($messageExploded[2], strpos($messageExploded[2], '=') + 1);
            }

            $message = "Message: ".$message. "\nError Code: ". $errorCode. "\nStatus Code: ".$statusCode;
            if (array_key_exists('Detail', $errorObj['Fault']['Error'])) {
                $detail = $errorObj['Fault']['Error']['Detail'];
                $message = $message . "\nDetail: ".$detail;
            }
        }


        return [
            'error' => [
                'status' => $error->getHttpStatusCode(),
                'message' => $message
            ]
        ];
    }
}