<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Responses;

use Carbon\Carbon;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Quickbooks\Helpers\AddressMatchChecker;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;
use QuickBooksOnline\API\Data\IPPCustomer;

/**
 * Create Contact(s) Response
 * @package PHPAccounting\Quickbooks\Message\Contacts\Responses
 */
class CreateContactResponse extends AbstractResponse
{

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
                        $detail['detail'] ?? null,
                        'Contact');
                }
            } elseif (array_key_exists('status', $this->data)) {
                $detail = $this->data['detail'] ?? [];
                return ErrorResponseHelper::parseErrorResponse(
                    $detail['message'] ?? null,
                    $this->data['status'],
                    $detail['error_code'] ?? null,
                    $detail['status_code'] ?? null,
                    $detail['detail'] ?? null,
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

            $billingAddress = null;
            $shippingAddress = null;

            if ($contact->ShipAddr) {
                $shippingAddress = [
                    'address_line_1' => $contact->ShipAddr->Line1,
                    'city' => $contact->ShipAddr->City,
                    'postal_code' => $contact->ShipAddr->PostalCode,
                    'state' => $contact->ShipAddr->CountrySubDivisionCode,
                    'country' => $contact->ShipAddr->Country
                ];
            }

            if ($contact->BillAddr) {
                $billingAddress = [
                    'address_line_1' => $contact->BillAddr->Line1,
                    'city' => $contact->BillAddr->City,
                    'postal_code' => $contact->BillAddr->PostalCode,
                    'state' => $contact->BillAddr->CountrySubDivisionCode,
                    'country' => $contact->BillAddr->Country
                ];
            }

            if (AddressMatchChecker::doesAddressMatch($billingAddress, $shippingAddress)) {
                $newContact['addresses'][] = $billingAddress + ['address_type' => 'PRIMARY'];
            }
            else {
                if ($billingAddress) {
                    $newContact['addresses'][] = $billingAddress + ['address_type' => 'BILLING'];
                }
                if ($shippingAddress) {
                    $newContact['addresses'][] = $shippingAddress + ['address_type' => 'PRIMARY'];
                }
            }

            if ($contact->PrimaryEmailAddr) {
                $newContact['email_address'] = $contact->PrimaryEmailAddr->Address;
            }
            if ($contact->PrimaryPhone) {
                array_push($newContact['phones'], [
                    'type' =>  'DEFAULT',
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

                $billingAddress = null;
                $shippingAddress = null;

                if ($contact->ShipAddr) {
                    $shippingAddress = [
                        'address_line_1' => $contact->ShipAddr->Line1,
                        'city' => $contact->ShipAddr->City,
                        'postal_code' => $contact->ShipAddr->PostalCode,
                        'state' => $contact->ShipAddr->CountrySubDivisionCode,
                        'country' => $contact->ShipAddr->Country
                    ];
                }

                if ($contact->BillAddr) {
                    $billingAddress = [
                        'address_line_1' => $contact->BillAddr->Line1,
                        'city' => $contact->BillAddr->City,
                        'postal_code' => $contact->BillAddr->PostalCode,
                        'state' => $contact->BillAddr->CountrySubDivisionCode,
                        'country' => $contact->BillAddr->Country
                    ];
                }

                if (AddressMatchChecker::doesAddressMatch($billingAddress, $shippingAddress)) {
                    $newContact['addresses'][] = $billingAddress + ['address_type' => 'PRIMARY'];
                }
                else {
                    if ($billingAddress) {
                        $newContact['addresses'][] = $billingAddress + ['address_type' => 'BILLING'];
                    }
                    if ($shippingAddress) {
                        $newContact['addresses'][] = $shippingAddress + ['address_type' => 'PRIMARY'];
                    }
                }

                if ($contact->PrimaryEmailAddr) {
                    $newContact['email_address'] = $contact->PrimaryEmailAddr->Address;
                }
                if ($contact->PrimaryPhone) {
                    array_push($newContact['phones'], [
                        'type' =>  'DEFAULT',
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
