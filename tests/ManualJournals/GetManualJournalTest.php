<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 8/10/2019
 * Time: 2:24 PM
 */

namespace Tests\ManualJournals;


use Tests\BaseTest;

class GetManualJournalTest extends BaseTest
{
    public function testGetManualJournals()
    {
        $this->setUp();
        $params = [
            'accountingID' => "",
            'page' => 1
        ];
        try {
            $response = $this->gateway->getManualJournal($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getJournals());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}