<?php

namespace PHPAccounting\Quickbooks\Message\Journals\Response;

use Carbon\Carbon;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPJournalEntry;

class GetJournalResponse extends AbstractQuickbooksResponse
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
                $newJournalItem['is_credit'] = $journalItem->JournalEntryLineDetail->PostingType == 'Credit'? true : false;
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
                $journalItems[] = $newJournalItem;
            }

            $journal['journal_data'] = $journalItems;
        }
        return $journal;
    }

    private function parseData($journalEntry) {
        $newJournalEntry = [];
        $newJournalEntry['accounting_id'] = $journalEntry->Id;
        $newJournalEntry['reference_id'] = $journalEntry->DocNumber;
        $newJournalEntry['sync_token'] = $journalEntry->SyncToken;
        $newJournalEntry['date'] = $journalEntry->TxnDate;
        if ($journalEntry->MetaData->LastUpdatedTime) {
            $updatedAt = Carbon::parse($journalEntry->MetaData->LastUpdatedTime);
            $updatedAt->setTimezone('UTC');
            $newJournalEntry['updated_at'] = $updatedAt->toDateTimeString();
        }
        $newJournalEntry = $this->parseJournalItems($journalEntry->Line, $newJournalEntry);

        return $newJournalEntry;
    }
    /**
     * Return all JournalEntries with Generic Schema Variable Assignment
     * @return array
     */
    public function getJournals(){
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
