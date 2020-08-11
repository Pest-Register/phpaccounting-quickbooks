<?php


namespace PHPAccounting\Quickbooks\Helpers;


class ErrorResponseHelper
{
    /**
     * @param $response
     * @param string $model
     * @return array
     */
    public static function parseErrorResponse ($response, $model = '') {
        switch ($model) {
            default:
                if (strpos($response, 'Duplicate') !== false) {
                    $response = [ 'message' => 'Duplicate model found', 'exception' => $response ];
                } else if (strpos($response, 'Existing') !== false) {
                    $response = [ 'message' => 'No model found from given ID', 'exception' => $response];
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed') !== false) {
                    $response = [ 'message' => 'The access token has expired', 'exception' => $response];
                } elseif (strpos($response, 'Unsupported Operation') !== false) {
                    $response = ['message' => 'Model cannot be edited', 'exception' => $response];
                } elseif (strpos($response, 'A business validation error has occurred while processing your request') !== false) {
                    $response = [ 'message' => 'Validation exception. Possibly duplicate ID or business error', 'exception' => $response];
                } else {
                    $response = ['message' => $response, 'exception' => $response];
                }
                return $response;
        }
    }
}