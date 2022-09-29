<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 2/10/2019
 * Time: 3:59 PM
 */

namespace PHPAccounting\Quickbooks\Message\ManualJournals\Response;

use Carbon\Carbon;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPJournalEntry;

class UpdateManualJournalResponse extends AbstractQuickbooksResponse
{
    /**
     * Add LineItems to JournalEntry
     * @param $data
     * @param $journal
     * @return mixed
     */
    private function parseJournalItems($data, $journal) {
        if ($data) {
            $journalItems = [];
            foreach($data as $journalItem) {
                $newJournalItem = [];
                $newJournalItem['tax_amount'] = 0;
                $newJournalItem['accounting_id'] = $journalItem->Id;
                $newJournalItem['description'] = $journalItem->Description;
                $newJournalItem['is_credit'] = $journalItem->JournalEntryLineDetail->PostingType == 'Credit' ? true : false;
                $newJournalItem['gross_amount'] = $journalItem->Amount;

                if(isset($journalItem->JournalEntryLineDetail->AccountRef)){
                    $newJournalItem['account_code'] = $journalItem->JournalEntryLineDetail->AccountRef;
                }

                if(isset($journalItem->JournalEntryLineDetail->TaxCodeRef)){
                    $newJournalItem['tax_type_id'] = $journalItem->JournalEntryLineDetail->TaxCodeRef;
                }
                if(isset($journalItem->JournalEntryLineDetail->TaxAmount)){
                    $newJournalItem['tax_amount'] = $journalItem->JournalEntryLineDetail->TaxAmount;
                    $newJournalItem['net_amount'] = (float) $newJournalItem['tax_amount'] + (float) $newJournalItem['gross_amount'];
                } else {
                    $newJournalItem['net_amount'] = $newJournalItem['gross_amount'];
                }

                array_push($journalItems, $newJournalItem);
            }

            $journal['journal_data'] = $journalItems;
        }
        return $journal;
    }

    /**
     * @param $journalEntry
     * @return mixed
     */
    private function parseData($journalEntry) {
        $newJournalEntry = [];
        $newJournalEntry['accounting_id'] = $journalEntry->Id;
        $newJournalEntry['reference_id'] = $journalEntry->DocNumber;
        $newJournalEntry['sync_token'] = $journalEntry->SyncToken;
        $newJournalEntry['date'] = $journalEntry->TxnDate;
        $newJournalEntry['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $journalEntry->MetaData->LastUpdatedTime)->toDateTimeString();
        $newJournalEntry = $this->parseJournalItems($journalEntry->Line, $newJournalEntry);

        return $newJournalEntry;
    }
    /**
     * Return all JournalEntries with Generic Schema Variable Assignment
     * @return array
     */
    public function getManualJournals(){
        $journalEntries = [];
        if ($this->data instanceof IPPJournalEntry){
            $newJournalEntry = $this->parseData($this->data);
            $journalEntries[] = $newJournalEntry;

        } else {
            foreach ($this->data as $journalEntry) {
                $newJournalEntry = $this->parseData($journalEntry);
                $journalEntries[] = $newJournalEntry;
            }
        }

        return $journalEntries;
    }
}
