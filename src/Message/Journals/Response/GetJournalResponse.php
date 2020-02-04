<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 30/09/2019
 * Time: 3:02 PM
 */

namespace PHPAccounting\Quickbooks\Message\Journals\Response;


use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use PHPAccounting\Quickbooks\Message\Response;
use QuickBooksOnline\API\Data\IPPJournalEntry;

class GetJournalResponse extends AbstractResponse
{

    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data) {
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    return ErrorResponseHelper::parseErrorResponse($this->data['error']['detail']['message'], 'Journal');
                }
            } elseif (array_key_exists('status', $this->data)) {
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'], 'Journal');
            }
        } else {
            return 'NULL Returned from API or End of Pagination';
        }

        return null;
    }


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
                $newJournalItem['gross_amount'] = 0;
                $newJournalItem['net_amount'] = 0;
                $newJournalItem['accounting_id'] = $journalItem->Id;
                $newJournalItem['description'] = $journalItem->Description;
                $newJournalItem['is_credit'] = $journalItem->JournalEntryLineDetail->PostingType == 'Credit'? true : false;
                $newJournalItem['gross_amount'] = $journalItem->Amount;
                if(isset($journalItem->JournalEntryLineDetail->AccountRef)){
                    $newJournalItem['account_code'] = $journalItem->JournalEntryLineDetail->AccountRef;
                }

                if(isset($journalItem->JournalEntryLineDetail->TaxCodeRef)){
                    $newJournalItem['tax_type'] = $journalItem->JournalEntryLineDetail->TaxCodeRef;
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
     * Return all JournalEntries with Generic Schema Variable Assignment
     * @return array
     */
    public function getJournals(){
        $journalEntries = [];
        if ($this->data instanceof IPPJournalEntry){
            $journalEntry = $this->data;
            $newJournalEntry = [];
            $newJournalEntry['accounting_id'] = $journalEntry->Id;
            $newJournalEntry['reference_id'] = $journalEntry->DocNumber;
            $newJournalEntry['sync_token'] = $journalEntry->SyncToken;
            $newJournalEntry['date'] = $journalEntry->TxnDate;
            $newJournalEntry['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $journalEntry->MetaData->LastUpdatedTime)->toDateTimeString();
            $newJournalEntry = $this->parseJournalItems($journalEntry->Line, $newJournalEntry);

            array_push($journalEntries, $newJournalEntry);

        } else {
            foreach ($this->data as $journalEntry) {
                $newJournalEntry = [];
                $newJournalEntry['accounting_id'] = $journalEntry->Id;
                $newJournalEntry['reference_id'] = $journalEntry->DocNumber;
                $newJournalEntry['sync_token'] = $journalEntry->SyncToken;
                $newJournalEntry['date'] = $journalEntry->TxnDate;
                $newJournalEntry['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $journalEntry->MetaData->LastUpdatedTime)->toDateTimeString();
                $newJournalEntry = $this->parseJournalItems($journalEntry->Line, $newJournalEntry);
                array_push($journalEntries, $newJournalEntry);
            }
        }

        return $journalEntries;
    }
}