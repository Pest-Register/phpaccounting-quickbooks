<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 8/10/2019
 * Time: 2:24 PM
 */

namespace Tests\ManualJournals;


use Faker\Provider\Base;
use PHPUnit\Framework\TestCase;
use Tests\BaseTest;

class DeleteManualJournalTest extends BaseTest
{
    public function testDeleteManualJournal(){
        $this->setUp();
        try {
            $params = [
                "accounting_id" => "1",
                "sync_token" => 0
            ];

            $response = $this->gateway->deleteManualJournal($params)->send();
            if ($response->isSuccessful()) {
                $this->assertTrue($response->isSuccessful());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}