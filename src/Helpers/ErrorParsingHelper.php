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
        $errorResponse = [];

        if (array_key_exists('Message', $errorObj['Fault']['Error'])) {
            $messageExploded = explode(';', $errorObj['Fault']['Error']['Message']);

            if (array_key_exists(0, $messageExploded)) {
                $message = substr($messageExploded[0], strpos($messageExploded[0], "="));
                $errorResponse['message'] = $message;
            }

            if (array_key_exists('code', $errorObj['Fault']['Error']['@attributes'])) {
                $errorCode = $errorObj['Fault']['Error']['@attributes']['code'];
                $errorResponse['error_code'] = $errorCode;
            }
            if (array_key_exists('code', $errorObj['Fault']['Error']['@attributes'])) {
                $statusCode = $errorObj['Fault']['Error']['@attributes']['code'];
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

    /**
     * If the QB base package fails to parse a payload correctly, it will throw generic exceptions.
     * These exceptions are handled here
     * @param \Exception $exception
     * @return array[]
     */
    public static function parseQbPackageError (\Throwable $exception) {
        return [
            'status' => 'error',
            'detail' => [
                'message' => $exception->getMessage(),
                'error_code' => -1
            ]
        ];
    }
}
