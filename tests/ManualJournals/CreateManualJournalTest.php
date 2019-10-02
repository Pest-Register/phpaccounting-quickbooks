<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 2/10/2019
 * Time: 2:01 PM
 */

namespace Tests\ManualJournals;


use Tests\BaseTest;

class CreateManualJournalTest extends BaseTest
{
    public function testCreateManualJournal(){
        $this->setUp();
        try {
            $params = [
                "Line" => [
                    [
                        "Description" => "nov portion of rider insurance",
                        "Amount" => 100.0,
                        "DetailType" => "JournalEntryLineDetail",
                        "JournalEntryLineDetail" => [
                            "PostingType" => "Debit",
                            "AccountRef" => [
                                "value" => "39",
                                "name" => "Opening Bal Equity"
                            ]
                        ]
                    ],
                    [
                        "Description" => "nov portion of rider insurance",
                        "Amount" => 100.0,
                        "DetailType" => "JournalEntryLineDetail",
                        "JournalEntryLineDetail" => [
                            "PostingType" => "Credit",
                            "AccountRef" => [
                                "value" => "44",
                                "name" => "Notes Payable"
                            ]
                        ]
                    ]
                ]
            ];

            $response = $this->gateway->createManualJournal($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getManualJournals());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}