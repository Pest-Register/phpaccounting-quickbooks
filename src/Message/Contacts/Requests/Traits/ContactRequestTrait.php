<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Requests\Traits;

use PHPAccounting\Quickbooks\Helpers\AddressMatchChecker;
use PHPAccounting\Quickbooks\Helpers\PhoneChecker;

trait ContactRequestTrait
{
    /**
     * Get Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getSyncToken(){
        return $this->getParameter('sync_token');
    }

    /**
     * Set Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param string $value Account Code
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
    }

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Name
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Set First Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact First Name
     */
    public function setFirstName($value) {
        return $this->setParameter('first_name', $value);
    }

    /**
     * Set Last Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Last Name
     */
    public function setLastName($value) {
        return $this->setParameter('last_name', $value);
    }

    /**
     * Set Is Individual Boolean Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Individual Status
     */
    public function setIsIndividual($value) {
        return $this->setParameter('is_individual', $value);
    }

    /**
     * Get Email Address Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     */
    public function getEmailAddress(){
        return $this->getParameter('email_address');
    }

    /**
     * Set Email Address Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Email Address
     */
    public function setEmailAddress($value){
        return $this->setParameter('email_address', $value);
    }

    /**
     * Get Website Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     */
    public function getWebsite(){
        return $this->getParameter('website');
    }

    /**
     * Set Website Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Email Address
     */
    public function setWebsite($value){
        return $this->setParameter('website', $value);
    }

    /**
     * Set Phones Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param array $value Array of Contact Phone Numbers
     */
    public function setPhones($value){
        return $this->setParameter('phones', $value);
    }

    /**
     * Get Phones Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/contactgroups
     * @return mixed
     */
    public function getPhones(){
        return $this->getParameter('phones');
    }

    /**
     * Set Addresses Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param array $value Array of Contact Addresses
     */
    public function setAddresses($value){
        return $this->setParameter('addresses', $value);
    }

    /**
     * Set Contact Groups Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param array $value Array of Contact Groups
     */
    public function setContactGroups($value) {
        return $this->setParameter('contact_groups', $value);
    }

    /**
     * Get ContactGroups Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/contactgroups
     * @return mixed
     */
    public function getContactGroups() {
        return $this->getParameter('contact_groups');
    }

    /**
     * Get Addresses Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/contactgroups
     * @return mixed
     */
    public function getAddresses(){
        return $this->getParameter('addresses');
    }


    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param $value
     * @return mixed
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }


    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param $value
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Get Address Array with Address Details for Contact
     * @access public
     * @param array $data Array of Quickbooks Addresses
     * @return array
     */
    public function getAddressData($data, $contact) {
        $primaryAddress = null;
        $billingAddress = null;

        foreach ($data as $address) {
            $type = $address['type'];
            if ($type == 'PRIMARY') {
                $primaryAddress = $address;
            }
            else if ($type == 'BILLING') {
                $billingAddress = $address;
            }
        }

        if (($primaryAddress && !$billingAddress) || AddressMatchChecker::doesAddressMatch($primaryAddress, $billingAddress)) {
            $contact['BillAddr'] = AddressMatchChecker::standardise($primaryAddress);
            $contact['ShipAddr'] = AddressMatchChecker::standardise($primaryAddress);
        }
        else {
            if ($primaryAddress) {
                $contact['ShipAddr'] = AddressMatchChecker::standardise($primaryAddress);
            }
            if ($billingAddress) {
                $contact['BillAddr'] = AddressMatchChecker::standardise($billingAddress);
            }
        }

        return $contact;
    }


    /**
     * Get Phones Array with Phone Details for Contact
     * @access public
     * @param array $data Array of Quickbooks Phones
     * @param $contact
     * @return array
     */
    public function getPhoneData($data, $contact) {
        foreach($data as $phone) {
            switch ($phone['type']) {
                case 'DEFAULT':
                    $contact['PrimaryPhone'] = ['FreeFormNumber' => PhoneChecker::standardise($phone)];
                    break;
                case 'MOBILE':
                    $contact['Mobile'] = ['FreeFormNumber' => PhoneChecker::standardise($phone)];
                    break;
                case 'FAX':
                    $contact['Fax'] = ['FreeFormNumber' => PhoneChecker::standardise($phone)];
                    break;
                default:
                    if (!array_key_exists('AlternatePhone', $contact)) {
                        $contact['AlternatePhone'] = ['FreeFormNumber' => PhoneChecker::standardise($phone)];
                        break;
                    }
                    break;
            }
        }
        return $contact;
    }
}