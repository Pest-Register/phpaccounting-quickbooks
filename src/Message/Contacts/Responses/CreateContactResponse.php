<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use QuickBooksOnline\API\Data\IPPCustomer;

/**
 * Create Contact(s) Response
 * @package PHPAccounting\Quickbooks\Message\Contacts\Responses
 */
class CreateContactResponse extends AbstractResponse
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
            $newContact['type'] = ['CUSTOMER'];
            $newContact['sync_token'] = $contact->SyncToken;
            $newContact['is_individual'] = ($contact->CompanyName ? true : false);
            $newContact['tax_type'] = $contact->DefaultTaxCodeRef;
            $newContact['currency_code'] = $contact->CurrencyRef;
            $newContact['updated_at'] = $contact->MetaData->LastUpdatedTime;
            if ($contact->ShipAddr) {
                array_push($newContact['addresses'], [
                    'address_type' =>  'STREET',
                    'address_line_1' => $contact->ShipAddr->Line1,
                    'city' => $contact->ShipAddr->City,
                    'postal_code' => $contact->ShipAddr->PostalCode,
                    'country' => $contact->ShipAddr->Country
                ]);
            }
            if ($contact->BillAddr) {
                array_push($newContact['addresses'], [
                    'address_type' =>  'POBOX',
                    'address_line_1' => $contact->BillAddr->Line1,
                    'city' => $contact->BillAddr->City,
                    'postal_code' => $contact->BillAddr->PostalCode,
                    'country' => $contact->BillAddr->Country
                ]);
            }
            if ($contact->OtherAddr) {
                array_push($newContact['addresses'], [
                    'type' =>  'EXTRA',
                    'address_line_1' => $contact->OtherAddr->Line1,
                    'city' => $contact->OtherAddr->City,
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
                    'type' =>  'OTHER',
                    'area_code' => '',
                    'country_code' => '',
                    'phone_number' => $contact->Mobile->FreeFormNumber
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
                $newContact['type'] = ['CUSTOMER'];
                $newContact['sync_token'] = $contact->SyncToken;
                $newContact['is_individual'] = ($contact->CompanyName ? true : false);
                $newContact['tax_type'] = $contact->DefaultTaxCodeRef;
                $newContact['currency_code'] = $contact->CurrencyRef;
                $newContact['updated_at'] = $contact->MetaData->LastUpdatedTime;
                if ($contact->ShipAddr) {
                    array_push($newContact['addresses'], [
                        'address_type' =>  'STREET',
                        'address_line_1' => $contact->ShipAddr->Line1,
                        'city' => $contact->ShipAddr->City,
                        'postal_code' => $contact->ShipAddr->PostalCode,
                        'country' => $contact->ShipAddr->Country
                    ]);
                }
                if ($contact->BillAddr) {
                    array_push($newContact['addresses'], [
                        'address_type' =>  'POBOX',
                        'address_line_1' => $contact->BillAddr->Line1,
                        'city' => $contact->BillAddr->City,
                        'postal_code' => $contact->BillAddr->PostalCode,
                        'country' => $contact->BillAddr->Country
                    ]);
                }
                if ($contact->OtherAddr) {
                    array_push($newContact['addresses'], [
                        'type' =>  'EXTRA',
                        'address_line_1' => $contact->OtherAddr->Line1,
                        'city' => $contact->OtherAddr->City,
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
                        'type' =>  'OTHER',
                        'area_code' => '',
                        'country_code' => '',
                        'phone_number' => $contact->Mobile->FreeFormNumber
                    ]);
                }
                array_push($contacts, $newContact);
            }
        }

        return $contacts;
    }
}