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
                    $response = [ 'message' => 'Duplicate model found' ];
                } else if (strpos($response, 'Existing') !== false) {
                    $response = [ 'message' => 'No model found from given ID' ];
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed') !== false) {
                    $response = [ 'message' => 'The access token has expired' ];
                } elseif (strpos($response, 'Unsupported Operation') !== false) {
                    $response = ['message' => 'Model cannot be edited' ];
                } elseif (strpos($response, 'A business validation error has occurred while processing your request') !== false) {
                    $response = [ 'message' => 'Validation exception. Possibly duplicate ID or business error' ];
                } else {
                    $response = ['message' => $response];
                }
                return $response;
        }
    }
}