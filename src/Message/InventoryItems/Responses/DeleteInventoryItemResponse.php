<?php

namespace PHPAccounting\Quickbooks\Message\InventoryItems\Responses;

use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;

class DeleteInventoryItemResponse extends AbstractQuickbooksResponse
{

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInventoryItems(){
        return [];
    }
}
