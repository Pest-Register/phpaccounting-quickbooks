<?php


namespace PHPAccounting\Quickbooks\Message\Accounts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use QuickBooksOnline\API\Data\IPPAccount;

class UpdateAccountResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if (array_key_exists('error', $this->data)) {
            if ($this->data['error']['status']){
                return false;
            }
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data['error']['status']){
            if (strpos($this->data['error']['detail'], 'Token expired') !== false) {
                return 'The access token has expired';
            } else {
                return $this->data['error']['detail'];
            }
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