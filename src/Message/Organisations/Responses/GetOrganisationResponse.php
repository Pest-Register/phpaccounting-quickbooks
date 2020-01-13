<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:09 AM
 */

namespace PHPAccounting\Quickbooks\Message\Organisations\Responses;

use Omnipay\Common\Message\AbstractResponse;
use QuickBooksOnline\API\Data\IPPAccount;
use QuickBooksOnline\API\Data\IPPCompanyAccountingPrefs;
use QuickBooksOnline\API\Data\IPPCompanyInfo;
use QuickBooksOnline\API\Data\IPPPreferences;

class GetOrganisationResponse extends AbstractResponse
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
            if ($this->data['error']['status']){
                if (strpos($this->data['error']['message'], 'Token expired') !== false || strpos($this->data['error']['message'], 'AuthenticationFailed') !== false) {
                    return 'The access token has expired';
                } else {
                    return $this->data['error']['message'];
                }
            }
        } else {
            return 'NULL Returned from API';
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