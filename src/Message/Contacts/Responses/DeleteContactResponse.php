<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;

class DeleteContactResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if (array_key_exists('status', $this->data)) {
                if (is_array($this->data)) {
                    if ($this->data['status'] == 'error') {
                        return false;
                    }
                } else {
                    if ($this->data->status == 'error') {
                        return false;
                    }
                }
            }
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    $detail = $this->data['error']['detail'] ?? [];
                    return ErrorResponseHelper::parseErrorResponse(
                        $detail['message'] ?: null,
                        $this->data['error']['status'],
                        $detail['error_code'] ?: null,
                        $detail['status_code'] ?: null,
                        $detail['detail'] ?: null,
                        'Account');
                }
            } elseif (array_key_exists('status', $this->data)) {
                $detail = $this->data['detail'] ?? [];
                return ErrorResponseHelper::parseErrorResponse(
                    $detail['message'] ?: null,
                    $this->data['status'],
                    $detail['error_code'] ?: null,
                    $detail['status_code'] ?: null,
                    $detail['detail'] ?: null,
                    'Contact');
            }
        } else {
            return [
                'message' => 'NULL Returned from API or End of Pagination',
                'exception' =>'NULL Returned from API or End of Pagination',
                'error_code' => null,
                'status_code' => null,
                'detail' => null
            ];
        }

        return null;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getContacts(){

        return [];
    }
}
