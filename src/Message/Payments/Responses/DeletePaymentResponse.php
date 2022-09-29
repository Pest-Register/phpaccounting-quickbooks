<?php


namespace PHPAccounting\Quickbooks\Message\Payments\Responses;

use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;

class DeletePaymentResponse extends AbstractQuickbooksResponse
{

    /**
     * Return all JournalEntries with Generic Schema Variable Assignment
     * @return array
     */
    public function getPayments(){
        return [];
    }
}
