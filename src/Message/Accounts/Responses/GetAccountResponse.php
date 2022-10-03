<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Responses;

use Carbon\Carbon;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPAccount;

/**
 * Get Account(s) Response
 * @package PHPAccounting\Quickbooks\Message\ContactGroups\Responses
 */
class GetAccountResponse extends AbstractQuickbooksResponse
{

    /**
     * Parse data returned from provider
     * @param $account
     * @return array
     */
    private function parseData($account) {
        $newAccount = [];
        $newAccount['accounting_id'] = $account->Id;
        $newAccount['code'] = $account->Id;
        $newAccount['name'] = $account->Name;
        $newAccount['description'] = $account->Description;
        $newAccount['type'] = $account->AccountType;
        $newAccount['sync_token'] = $account->SyncToken;
        $newAccount['is_bank_account'] = $account->OnlineBankingEnabled;
        $newAccount['enable_payments_to_account'] = ($account->OnlineBankingEnabled ? true : false);
        $newAccount['tax_type_id'] = $account->TaxCodeRef;
        $newAccount['bank_account_number'] = $account->BankNum;
        $newAccount['currency_code'] = $account->CurrencyRef;
        if ($account->MetaData->LastUpdatedTime) {
            $updatedAt = Carbon::parse($account->MetaData->LastUpdatedTime);
            $updatedAt->setTimezone('UTC');
            $newAccount['updated_at'] = $updatedAt->toDateTimeString();
        }

        return $newAccount;
    }

    /**
     * Return all Accounts with Generic Schema Variable Assignment
     * @return array
     */
    public function getAccounts(){
        $accounts = [];
        if ($this->data instanceof IPPAccount){
            $newAccount = $this->parseData($this->data);
            $accounts[] = $newAccount;
        }
        else {
            foreach ($this->data as $account) {
                $newAccount = $this->parseData($account);
                $accounts[] = $newAccount;
            }
        }

        return $accounts;
    }
}
