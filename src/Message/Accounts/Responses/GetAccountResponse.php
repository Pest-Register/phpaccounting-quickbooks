<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use QuickBooksOnline\API\Data\IPPAccount;

/**
 * Get Account(s) Response
 * @package PHPAccounting\Quickbooks\Message\ContactGroups\Responses
 */
class GetAccountResponse extends AbstractResponse
{

    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if($this->data->status){
            return $this->data->status;
        }
        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if($this->data->status){
            return $this->data;
        }
        return null;
    }

    /**
     * Return all Contact Groups with Generic Schema Variable Assignment
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
            $newAccount['is_bank_account'] = $account->OnlineBankingEnabled;
            $newAccount['enable_payments_to_account'] = ($account->OnlineBankingEnabled ? true : false);
            $newAccount['tax_type'] = $account->TaxCodeRef;
            $newAccount['bank_account_number'] = $account->BankNum;
            $newAccount['currency_code'] = $account->CurrencyRef;
            $newAccount['updated_at'] = $account->MetaData->LastUpdatedTime;
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
                $newAccount['is_bank_account'] = $account->OnlineBankingEnabled;
                $newAccount['enable_payments_to_account'] = ($account->OnlineBankingEnabled ? true : false);
                $newAccount['tax_type'] = $account->TaxCodeRef;
                $newAccount['bank_account_number'] = $account->BankNum;
                $newAccount['currency_code'] = $account->CurrencyRef;
                $newAccount['updated_at'] = $account->MetaData->LastUpdatedTime;
                array_push($accounts, $newAccount);
            }
        }

        return $accounts;
    }
}