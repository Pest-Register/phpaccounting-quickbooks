<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 2/10/2019
 * Time: 2:01 PM
 */

namespace Tests\ManualJournals;


use Tests\BaseTest;

class UpdateManualJournalTest extends BaseTest
{
    public function testUpdateManualJournal(){
        $this->setUp();
        try {
            $params = [
                "accounting_id" => 145,
                "narration" => 'a manual journal entry',
                "reference_id" => "298u3nd",
                "journal_data" => [
                    [
                        "is_credit" => true,
                        "gross_amount" => 100.0,
                        "account_code" => 999,
                        "account_id" => 1,
                        "accounting_id" => 0,
                        "description" => "test transaction"
                    ],
                    [
                        "is_credit" => false,
                        "gross_amount" => 100.0,
                        "account_code" => 998,
                        "account_id" => 2,
                        "accounting_id" => 1,
                        "description" => "test transaction",
                    ]
                ]
            ];

            $response = $this->gateway->updateManualJournal($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getManualJournals());
            } else {
                var_dump($response->getErrorMessage());
            }

        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}