<?php
namespace PHPAccounting\Quickbooks\Message\Contacts\Responses;

use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use QuickBooksOnline\API\Data\IPPCustomer;

/**
 * Get Contact(s) Response
 * @package PHPAccounting\Quickbooks\Message\Contacts\Responses
 */
class GetContactResponse extends AbstractResponse
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
            $errorCode = '';
            $statusCode = '';
            $detail = '';
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    if (array_key_exists('error_code', $this->data['error']['detail'])) {
                        $errorCode = $this->data['error']['detail']['error_code'];
                    }
                    if (array_key_exists('status_code', $this->data['error']['detail'])) {
                        $statusCode = $this->data['error']['detail']['status_code'];
                    }
                    if (array_key_exists('detail', $this->data['error']['detail'])){
                        $detail = $this->data['error']['detail']['detail'];
                    }
                    return ErrorResponseHelper::parseErrorResponse(
                        $this->data['error']['detail']['message'],
                        $this->data['error']['status'],
                        $errorCode,
                        $statusCode,
                        $detail,
                        'Contact');
                }
            } elseif (array_key_exists('status', $this->data)) {
                if (array_key_exists('error_code', $this->data['detail'])) {
                    $errorCode = $this->data['detail']['error_code'];
                }
                if (array_key_exists('status_code', $this->data['detail'])) {
                    $statusCode = $this->data['detail']['status_code'];
                }
                if (array_key_exists('detail', $this->data['detail'])){
                    $detail = $this->data['detail']['detail'];
                }
                return ErrorResponseHelper::parseErrorResponse(
                    $this->data['detail']['message'],
                    $this->data['status'],
                    $errorCode,
                    $statusCode,
                    $detail,
                    'Contact');
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

    /**
     * Return all Contacts with Generic Schema Variable Assignment
     * @return array
     */
    public function getContacts(){
        $contacts = [];
        if ($this->data instanceof IPPCustomer){
            $contact = $this->data;
            $newContact = [];
            $newContact['addresses'] = [];
            $newContact['phones'] = [];
            $newContact['accounting_id'] = $contact->Id;
            $newContact['display_name'] = $contact->DisplayName;
            $newContact['first_name'] = $contact->GivenName;
            $newContact['last_name'] = $contact->FamilyName;
            $newContact['types'] = ['CUSTOMER'];
            $newContact['sync_token'] = $contact->SyncToken;
            $newContact['is_individual'] = ($contact->CompanyName ? true : false);
            $newContact['tax_type'] = $contact->DefaultTaxCodeRef;
            $newContact['currency_code'] = $contact->CurrencyRef;
            $newContact['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $contact->MetaData->LastUpdatedTime)->toDateTimeString();
            if ($contact->Active) {
                if ($contact->Active == true) {
                    $newContact['status'] = 'ACTIVE';
                } else {
                    $newContact['status'] = 'INACTIVE';
                }
            }
            if ($contact->WebAddr) {
                $newContact['website'] = $contact->WebAddr->URI;
            }
            if ($contact->ShipAddr) {
                array_push($newContact['addresses'], [
                    'address_type' =>  'PRIMARY',
                    'address_line_1' => $contact->ShipAddr->Line1,
                    'city' => $contact->ShipAddr->City,
                    'postal_code' => $contact->ShipAddr->PostalCode,
                    'state' => $contact->ShipAddr->CountrySubDivisionCode,
                    'country' => $contact->ShipAddr->Country
                ]);
            }
            if ($contact->BillAddr) {
                array_push($newContact['addresses'], [
                    'address_type' =>  'BILLING',
                    'address_line_1' => $contact->BillAddr->Line1,
                    'city' => $contact->BillAddr->City,
                    'postal_code' => $contact->BillAddr->PostalCode,
                    'state' => $contact->BillAddr->CountrySubDivisionCode,
                    'country' => $contact->BillAddr->Country
                ]);
            }
            if ($contact->OtherAddr) {
                array_push($newContact['addresses'], [
                    'type' =>  'EXTRA',
                    'address_line_1' => $contact->OtherAddr->Line1,
                    'city' => $contact->OtherAddr->City,
                    'state' => $contact->OtherAddr->CountrySubDivisionCode,
                    'postal_code' => $contact->OtherAddr->PostalCode,
                    'country' => $contact->OtherAddr->Country
                ]);
            }
            if ($contact->PrimaryEmailAddr) {
                $newContact['email_address'] = $contact->PrimaryEmailAddr->Address;
            }
            if ($contact->PrimaryPhone) {
                array_push($newContact['phones'], [
                    'type' =>  'BUSINESS',
                    'area_code' => '',
                    'country_code' => '',
                    'phone_number' => $contact->PrimaryPhone->FreeFormNumber
                ]);
            }
            if ($contact->Mobile) {
                array_push($newContact['phones'], [
                    'type' =>  'MOBILE',
                    'area_code' => '',
                    'country_code' => '',
                    'phone_number' => $contact->Mobile->FreeFormNumber
                ]);
            }
            if ($contact->AlternatePhone) {
                array_push($newContact['phones'], [
                    'type' =>  'EXTRA',
                    'area_code' => '',
                    'country_code' => '',
                    'phone_number' => $contact->AlternatePhone->FreeFormNumber
                ]);
            }
            array_push($contacts, $newContact);
        }
        else {
            foreach ($this->data as $contact) {
                $newContact = [];
                $newContact['addresses'] = [];
                $newContact['phones'] = [];
                $newContact['accounting_id'] = $contact->Id;
                $newContact['display_name'] = $contact->DisplayName;
                $newContact['first_name'] = $contact->GivenName;
                $newContact['last_name'] = $contact->FamilyName;
                $newContact['types'] = ['CUSTOMER'];
                $newContact['sync_token'] = $contact->SyncToken;
                $newContact['is_individual'] = ($contact->CompanyName ? true : false);
                $newContact['tax_type'] = $contact->DefaultTaxCodeRef;
                $newContact['currency_code'] = $contact->CurrencyRef;
                $newContact['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s-H:i', $contact->MetaData->LastUpdatedTime)->toDateTimeString();
                if ($contact->Active) {
                    if ($contact->Active == true) {
                        $newContact['status'] = 'ACTIVE';
                    } else {
                        $newContact['status'] = 'INACTIVE';
                    }
                }
                if ($contact->WebAddr) {
                    $newContact['website'] = $contact->WebAddr->URI;
                }
                if ($contact->ShipAddr) {
                    array_push($newContact['addresses'], [
                        'address_type' =>  'PRIMARY',
                        'address_line_1' => $contact->ShipAddr->Line1,
                        'city' => $contact->ShipAddr->City,
                        'state' => $contact->ShipAddr->CountrySubDivisionCode,
                        'postal_code' => $contact->ShipAddr->PostalCode,
                        'country' => $contact->ShipAddr->Country
                    ]);
                }
                if ($contact->BillAddr) {
                    array_push($newContact['addresses'], [
                        'address_type' =>  'BILLING',
                        'address_line_1' => $contact->BillAddr->Line1,
                        'city' => $contact->BillAddr->City,
                        'state' => $contact->BillAddr->CountrySubDivisionCode,
                        'postal_code' => $contact->BillAddr->PostalCode,
                        'country' => $contact->BillAddr->Country
                    ]);
                }
                if ($contact->OtherAddr) {
                    array_push($newContact['addresses'], [
                        'type' =>  'EXTRA',
                        'address_line_1' => $contact->OtherAddr->Line1,
                        'city' => $contact->OtherAddr->City,
                        'state' => $contact->OtherAddr->CountrySubDivisionCode,
                        'postal_code' => $contact->OtherAddr->PostalCode,
                        'country' => $contact->OtherAddr->Country
                    ]);
                }
                if ($contact->PrimaryEmailAddr) {
                    $newContact['email_address'] = $contact->PrimaryEmailAddr->Address;
                }
                if ($contact->PrimaryPhone) {
                    array_push($newContact['phones'], [
                        'type' =>  'BUSINESS',
                        'area_code' => '',
                        'country_code' => '',
                        'phone_number' => $contact->PrimaryPhone->FreeFormNumber
                    ]);
                }
                if ($contact->Mobile) {
                    array_push($newContact['phones'], [
                        'type' =>  'MOBILE',
                        'area_code' => '',
                        'country_code' => '',
                        'phone_number' => $contact->Mobile->FreeFormNumber
                    ]);
                }
                if ($contact->AlternatePhone) {
                    array_push($newContact['phones'], [
                        'type' =>  'EXTRA',
                        'area_code' => '',
                        'country_code' => '',
                        'phone_number' => $contact->AlternatePhone->FreeFormNumber
                    ]);
                }
                array_push($contacts, $newContact);
            }
        }

        return $contacts;
    }
}