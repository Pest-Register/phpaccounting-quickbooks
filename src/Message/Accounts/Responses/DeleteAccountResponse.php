<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Responses;

use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use QuickBooksOnline\API\Data\IPPAccount;

/**
 * Delete ContactGroup(s) Response
 * @package PHPAccounting\Quickbooks\Message\ContactGroups\Responses
 */
class DeleteAccountResponse extends AbstractResponse
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
                    return ErrorResponseHelper::parseErrorResponse($this->data['error']['detail']['message'], 'Account');
                }
            } elseif (array_key_exists('status', $this->data)) {
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'], 'Account');
            }
        } else {
            return ['message' => 'NULL Returned from API or End of Pagination', 'exception' =>'NULL Returned from API or End of Pagination' ];
        }

        return null;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getAccounts(){
        $accounts = [];
        if ($this->data instanceof IPPAccount){
            $account = $this->data;
            $newAccount = [];
            $newAccount['accounting_id'] = $account->Id;
            $newAccount['code'] = $account->AcctNum;
            $newAccount['name'] = $account->Name;
            $newAccount['description'] = $account->Description;
            $newAccount['type'] = $account->AccountType;
            $newAccount['sync_token'] = $account->SyncToken;
            $newAccount['is_bank_account'] = $account->OnlineBankingEnabled;
            $newAccount['enable_payments_to_account'] = ($account->OnlineBankingEnabled ? true : false);
            $newAccount['tax_type'] = $account->TaxCodeRef;
            $newAccount['bank_account_number'] = $account->BankNum;
            $newAccount['currency_code'] = $account->CurrencyRef;
            $newAccount['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $account->MetaData->LastUpdatedTime)->toDateTimeString();
            array_push($accounts, $newAccount);
        }
        else {
            foreach ($this->data as $account) {
                $newAccount = [];
                $newAccount['accounting_id'] = $account->Id;
                $newAccount['code'] = $account->AcctNum;
                $newAccount['name'] = $account->Name;
                $newAccount['description'] = $account->Description;
                $newAccount['type'] = $account->AccountType;
                $newAccount['sync_token'] = $account->SyncToken;
                $newAccount['is_bank_account'] = $account->OnlineBankingEnabled;
                $newAccount['enable_payments_to_account'] = ($account->OnlineBankingEnabled ? true : false);
                $newAccount['tax_type'] = $account->TaxCodeRef;
                $newAccount['bank_account_number'] = $account->BankNum;
                $newAccount['currency_code'] = $account->CurrencyRef;
                $newAccount['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $account->MetaData->LastUpdatedTime)->toDateTimeString();
                array_push($accounts, $newAccount);
            }
        }

        return $accounts;
    }
}