<?php

namespace PHPAccounting\Quickbooks\Message\Accounts\Responses;

use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;

/**
 * Delete ContactGroup(s) Response
 * @package PHPAccounting\Quickbooks\Message\ContactGroups\Responses
 */
class DeleteAccountResponse extends AbstractQuickbooksResponse
{

    /**
     * Return all Accounts with Generic Schema Variable Assignment
     * @return array
     */
    public function getAccounts(){

        return [];
    }
}
