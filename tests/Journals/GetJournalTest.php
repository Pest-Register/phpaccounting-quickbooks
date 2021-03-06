<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 3/10/2019
 * Time: 9:35 AM
 */

namespace Tests\Journals;


use Tests\BaseTest;

class GetJournalTest extends BaseTest
{
    public function testGetJournals()
    {
        $this->setUp();
        $params = [
            'accountingID' => "",
            'page' => 1
        ];
        try {
            $response = $this->gateway->getJournal($params)->send();
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