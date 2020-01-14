<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 2/10/2019
 * Time: 4:00 PM
 */

namespace PHPAccounting\Quickbooks\Message\ManualJournals\Response;


use Omnipay\Common\Message\AbstractResponse;

class DeleteManualJournalResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
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
            if ($this->data['error']['status']){
                if (strpos($this->data['error']['message'], 'Token expired') !== false || strpos($this->data['error']['message'], 'AuthenticationFailed') !== false) {
                    return 'The access token has expired';
                } else {
                    return $this->data['error']['message'];
                }
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
    public function getManualJournals(){
        return [];
    }
}