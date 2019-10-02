<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 30/09/2019
 * Time: 3:10 PM
 */

namespace PHPAccounting\XERO\Message\ManualJournals\Response;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\XERO\Message\ManualJournals\Request\GetManualJournalRequest;

class GetManualJournalResponse extends AbstractResponse
{

    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if (array_key_exists('error', $this->data)) {
            if ($this->data['error']['status']){
                return false;
            }
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data['error']['status']){
            if (strpos($this->data['error']['detail'], 'Token expired') !== false) {
                return 'The access token has expired';
            } else {
                return $this->data['error']['detail'];
            }
        }

        return null;
    }


    public function getOrganisations(){
        $organisations = [];
        if ($this->data instanceof IPPCompanyInfo){
            $organisation = $this->data;
            $newOrganisation = [];
            $newOrganisation['accounting_id'] = $organisation->Id;
            $newOrganisation['name'] = $organisation->CompanyName;
            $newOrganisation['country_code'] = $organisation->Country;
            $newOrganisation['legal_name'] = $organisation->LegalName;
            array_push($organisations, $newOrganisation);
        }

        return $organisations;
    }
}