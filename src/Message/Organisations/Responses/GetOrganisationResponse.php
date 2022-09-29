<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:09 AM
 */

namespace PHPAccounting\Quickbooks\Message\Organisations\Responses;

use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPCompanyInfo;
use QuickBooksOnline\API\Data\IPPPreferences;

class GetOrganisationResponse extends AbstractQuickbooksResponse
{

    public function getOrganisations(){
        $organisations = [];
        if ($this->data[0] instanceof IPPCompanyInfo){
            $organisation = $this->data[0];
            $newOrganisation = [];
            $newOrganisation['accounting_id'] = $organisation->Id;
            $newOrganisation['name'] = $organisation->CompanyName;
            $newOrganisation['country_code'] = $organisation->Country;
            $newOrganisation['legal_name'] = $organisation->LegalName;

            if($this->data[1] instanceof IPPPreferences) {
                $preferences = $this->data[1];
                $newOrganisation['gst_registered'] = $preferences->TaxPrefs->UsingSalesTax;
            }
            array_push($organisations, $newOrganisation);
        }

        return $organisations;
    }
}
