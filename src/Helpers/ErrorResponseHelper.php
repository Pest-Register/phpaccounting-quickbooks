<?php


namespace PHPAccounting\Quickbooks\Helpers;


class ErrorResponseHelper
{
    /**
     * @param $response
     * @param string $model
     * @return array
     */
    public static function parseErrorResponse ($response,$status, $errorCode, $statusCode, $detail, $model = '') {
        switch ($model) {
            default:
                if (strpos($response, 'Duplicate') !== false) {
                    $response = [
                        'message' => 'Duplicate model found',
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                } else if (strpos($response, 'Existing') !== false) {
                    $response = [
                        'message' => 'No model found from given ID',
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                } elseif (strpos($response, 'Token expired') !== false || strpos($response, 'AuthenticationFailed') !== false) {
                    $response = [
                        'message' => 'The access token has expired',
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                } elseif (strpos($response, 'Unsupported Operation') !== false) {
                    $response = [
                        'message' => 'Model cannot be edited',
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                } elseif (strpos($response, 'A business validation error has occurred while processing your request') !== false) {
                    $response = [
                        'message' => 'Validation exception. Possibly duplicate ID or business error',
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                } else {
                    $response = [
                        'message' => $response,
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                }

                return $response;
        }
    }
}