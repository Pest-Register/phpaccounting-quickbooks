<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Responses;

use Carbon\Carbon;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksResponse;
use QuickBooksOnline\API\Data\IPPCustomer;

/**
 * Create Contact(s) Response
 * @package PHPAccounting\Quickbooks\Message\Contacts\Responses
 */
class CreateContactResponse extends AbstractQuickbooksResponse
{
    /**
     * @param $contact
     * @return array
     */
    private function parseData($contact) {

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
        $newContact['tax_type_id'] = $contact->DefaultTaxCodeRef;
        $newContact['currency_code'] = $contact->CurrencyRef;
        if ($contact->MetaData->LastUpdatedTime) {
            $updatedAt = Carbon::parse($contact->MetaData->LastUpdatedTime);
            $updatedAt->setTimezone('UTC');
            $newContact['updated_at'] = $updatedAt->toDateTimeString();
        }

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
            $newContact['addresses'][] = [
                'address_type' => 'PRIMARY',
                'address_line_1' => $contact->ShipAddr->Line1,
                'city' => $contact->ShipAddr->City,
                'postal_code' => $contact->ShipAddr->PostalCode,
                'state' => $contact->ShipAddr->CountrySubDivisionCode,
                'country' => $contact->ShipAddr->Country
            ];
        }
        if ($contact->BillAddr) {
            $newContact['addresses'][] = [
                'address_type' => 'BILLING',
                'address_line_1' => $contact->BillAddr->Line1,
                'city' => $contact->BillAddr->City,
                'postal_code' => $contact->BillAddr->PostalCode,
                'state' => $contact->BillAddr->CountrySubDivisionCode,
                'country' => $contact->BillAddr->Country
            ];
        }
        if ($contact->OtherAddr) {
            $newContact['addresses'][] = [
                'type' => 'EXTRA',
                'address_line_1' => $contact->OtherAddr->Line1,
                'city' => $contact->OtherAddr->City,
                'state' => $contact->OtherAddr->CountrySubDivisionCode,
                'postal_code' => $contact->OtherAddr->PostalCode,
                'country' => $contact->OtherAddr->Country
            ];
        }
        if ($contact->PrimaryEmailAddr) {
            $newContact['email_address'] = $contact->PrimaryEmailAddr->Address;
        }
        if ($contact->PrimaryPhone) {
            $newContact['phones'][] = [
                'type' => 'BUSINESS',
                'area_code' => '',
                'country_code' => '',
                'phone_number' => $contact->PrimaryPhone->FreeFormNumber
            ];
        }
        if ($contact->Mobile) {
            $newContact['phones'][] = [
                'type' => 'MOBILE',
                'area_code' => '',
                'country_code' => '',
                'phone_number' => $contact->Mobile->FreeFormNumber
            ];
        }
        if ($contact->AlternatePhone) {
            $newContact['phones'][] = [
                'type' => 'EXTRA',
                'area_code' => '',
                'country_code' => '',
                'phone_number' => $contact->AlternatePhone->FreeFormNumber
            ];
        }

        return $newContact;
    }

    /**
     * Return all Contacts with Generic Schema Variable Assignment
     * @return array
     */
    public function getContacts(){
        $contacts = [];
        if ($this->data instanceof IPPCustomer){
            $newContact = $this->parseData($this->data);
            $contacts[] = $newContact;
        }
        else {
            foreach ($this->data as $contact) {
                $newContact = $this->parseData($contact);
                $contacts[] = $newContact;
            }
        }

        return $contacts;
    }
}
