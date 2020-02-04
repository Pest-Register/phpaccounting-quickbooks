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
            case 'Account':
                if (strpos($response, 'Duplicate') !== false) {
                    $response = 'Duplicate model found';
                } else if (strpos($response, 'Existing') !== false) {
                    $response = 'No model found from given ID';
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed')) {
                    $response = 'The access token has expired';
                }
                return $response;
                break;
            case 'Invoice':
                if (strpos($response, 'Duplicate') !== false) {
                    $response = 'Duplicate model found';
                } else if (strpos($response, 'Existing') !== false) {
                    $response = 'No model found from given ID';
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed')) {
                    $response = 'The access token has expired';
                }
                return $response;
                break;
            case 'Contact':
                if (strpos($response, 'Duplicate') !== false) {
                    $response = 'Duplicate model found';
                } else if (strpos($response, 'Existing') !== false) {
                    $response = 'No model found from given ID';
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed')) {
                    $response = 'The access token has expired';
                }
                return $response;
                break;
            case 'Inventory Item':
                if (strpos($response, 'Duplicate') !== false) {
                    $response = 'Duplicate model found';
                } else if (strpos($response, 'Existing') !== false) {
                    $response = 'No model found from given ID';
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed')) {
                    $response = 'The access token has expired';
                }
                return $response;
                break;
            case 'Payment':
                if (strpos($response, 'Duplicate') !== false) {
                    $response = 'Duplicate model found';
                } else if (strpos($response, 'Existing') !== false) {
                    $response = 'No model found from given ID';
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed')) {
                    $response = 'The access token has expired';
                }
                return $response;
            default:
                if (strpos($response, 'Duplicate') !== false) {
                    $response = 'Duplicate model found';
                } else if (strpos($response, 'Existing') !== false) {
                    $response = 'No model found from given ID';
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed')) {
                    $response = 'The access token has expired';
                }
                return $response;
        }
    }
}