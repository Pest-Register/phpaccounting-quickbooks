<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:09 AM
 */

namespace PHPAccounting\Quickbooks\Message\Organisations\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
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
            if (array_key_exists('status', $this->data)) {
                if (is_array($this->data)) {
                    if ($this->data['status'] == 'error') {
                        return false;
                    }
                } else {
                    if ($this->data->status == 'error') {
                        return false;
                    }
                }
            }
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
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    $detail = $this->data['error']['detail'] ?? [];
                    return ErrorResponseHelper::parseErrorResponse(
                        $detail['message'] ?? null,
                        $this->data['error']['status'],
                        $detail['error_code'] ?? null,
                        $detail['status_code'] ?? null,
                        $detail['detail'] ?? null
                    );
                }
            } elseif (array_key_exists('status', $this->data)) {
                $detail = $this->data['detail'] ?? [];
                return ErrorResponseHelper::parseErrorResponse(
                    $detail['message'] ?? null,
                    $this->data['status'],
                    $detail['error_code'] ?? null,
                    $detail['status_code'] ?? null,
                    $detail['detail'] ?? null
                );
            }
        } else {
            return [
                'message' => 'NULL Returned from API or End of Pagination',
                'exception' =>'NULL Returned from API or End of Pagination',
                'error_code' => null,
                'status_code' => null,
                'detail' => null
            ];
        }

        return null;
    }


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
