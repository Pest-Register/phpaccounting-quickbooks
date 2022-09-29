<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 2/10/2019
 * Time: 4:00 PM
 */

namespace PHPAccounting\Quickbooks\Message\ManualJournals\Response;

use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;

class DeleteManualJournalResponse extends AbstractQuickbooksResponse
{

    /**
     * Return all JournalEntries with Generic Schema Variable Assignment
     * @return array
     */
    public function getManualJournals(){
        return [];
    }
}
