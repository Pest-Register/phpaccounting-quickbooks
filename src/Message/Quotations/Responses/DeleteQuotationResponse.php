<?php

namespace PHPAccounting\Quickbooks\Message\Quotations\Responses;

use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;

class DeleteQuotationResponse extends AbstractQuickbooksResponse
{

    /**
     * Return all Quotations with Generic Schema Variable Assignment
     * @return array
     */
    public function getQuotations(){
        return [];
    }
}
