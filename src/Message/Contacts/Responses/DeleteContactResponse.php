<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Responses;

use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;

class DeleteContactResponse extends AbstractQuickbooksResponse
{

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getContacts(){
        return [];
    }
}
