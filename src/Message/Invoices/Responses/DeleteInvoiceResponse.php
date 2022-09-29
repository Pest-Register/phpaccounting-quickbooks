<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Responses;

use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;

class DeleteInvoiceResponse extends AbstractQuickbooksResponse
{

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInvoices(){
        return [];
    }
}
