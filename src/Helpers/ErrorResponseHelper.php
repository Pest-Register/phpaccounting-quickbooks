<?php


namespace PHPAccounting\Quickbooks\Helpers;


class ErrorResponseHelper
{
    /**
     * @param $response
     * @param string $model
     * @return string
     */
    public static function parseErrorResponse ($response, $model = '') {
        switch ($model) {
            default:
                if (strpos($response, 'Duplicate') !== false) {
                    $response = 'Duplicate model found';
                } else if (strpos($response, 'Existing') !== false) {
                    $response = 'No model found from given ID';
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed')) {
                    $response = 'The access token has expired';
                } elseif (strpos($response, 'Unsupported Operation') !== false) {
                    $response = 'Model cannot be edited';
                }
                return $response;
        }
    }
}