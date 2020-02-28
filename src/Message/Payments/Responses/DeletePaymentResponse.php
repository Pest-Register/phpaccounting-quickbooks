<?php


namespace PHPAccounting\Quickbooks\Message\Payments\Responses;



use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;

class DeletePaymentResponse extends AbstractResponse
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
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data) {
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    return ErrorResponseHelper::parseErrorResponse($this->data['error']['detail']['message'], 'Payment');
                }
            } elseif (array_key_exists('status', $this->data)) {
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'], 'Payment');
            }
        } else {
            return 'NULL Returned from API or End of Pagination';
        }

        return null;
    }

    /**
     * Return all JournalEntries with Generic Schema Variable Assignment
     * @return array
     */
    public function getPayments(){
        return [];
    }
}