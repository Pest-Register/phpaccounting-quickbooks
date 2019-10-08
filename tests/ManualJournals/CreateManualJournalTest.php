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
                "narration" => 'a manual journal entry',
                "reference_id" => "298u3nd",
                "journal_data" => [
                    [
                        "credit" => true,
                        "gross_amount" => 100.0,
                        "account_code" => 999,
                        "account_id" => 1,
                        "description" => "test transaction"
                    ],
                    [
                        "credit" => false,
                        "gross_amount" => 100.0,
                        "account_code" => 998,
                        "account_id" => 2,
                        "description" => "test transaction",
                    ]
                ]
            ];

            $response = $this->gateway->createManualJournal($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getJournals());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}