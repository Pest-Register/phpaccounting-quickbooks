<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Requests;

use Cassandra\Index;
use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\Contacts\Responses\CreateContactResponse;
use QuickBooksOnline\API\Facades\Customer;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;

/**
 * Create Contact(s)
 * @package PHPAccounting\Quickbooks\Message\Contacts\Requests
 */
class CreateContactRequest extends AbstractRequest
{
    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Name
     * @return CreateContactRequest
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Set First Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact First Name
     * @return CreateContactRequest
     */
    public function setFirstName($value) {
        return $this->setParameter('first_name', $value);
    }

    /**
     * Set Last Name Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Last Name
     * @return CreateContactRequest
     */
    public function setLastName($value) {
        return $this->setParameter('last_name', $value);
    }

    /**
     * Set Is Individual Boolean Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Individual Status
     * @return CreateContactRequest
     */
    public function setIsIndividual($value) {
        return $this->setParameter('is_individual', $value);
    }

    /**
     * Get Email Address Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @return CreateContactRequest
     */
    public function getEmailAddress(){
        return $this->getParameter('email_address');
    }

    /**
     * Set Email Address Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Email Address
     * @return CreateContactRequest
     */
    public function setEmailAddress($value){
        return $this->setParameter('email_address', $value);
    }

    /**
     * Get Website Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @return CreateContactRequest
     */
    public function getWebsite(){
        return $this->getParameter('website');
    }

    /**
     * Set Website Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param string $value Contact Email Address
     * @return CreateContactRequest
     */
    public function setWebsite($value){
        return $this->setParameter('website', $value);
    }

    /**
     * Set Phones Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param array $value Array of Contact Phone Numbers
     * @return CreateContactRequest
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
     * @return CreateContactRequest
     */
    public function setAddresses($value){
        return $this->setParameter('addresses', $value);
    }

    /**
     * Set Contact Groups Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/customer
     * @param array $value Array of Contact Groups
     * @return CreateContactRequest
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
     * Get Address Array with Address Details for Contact
     * @access public
     * @param array $data Array of Quickbooks Addresses
     * @return array
     */
    public function getAddressData($data, $contact) {
        foreach($data as $address) {
            switch ($address['type']) {
                case 'PRIMARY':
                    $contact['ShipAddr'] =
                        [
                            'Line1' => IndexSanityCheckHelper::indexSanityCheck('address_line_1', $address),
                            'City' => IndexSanityCheckHelper::indexSanityCheck('city', $address),
                            'Country' => IndexSanityCheckHelper::indexSanityCheck('country', $address),
                            'CountrySubDivisionCode' => IndexSanityCheckHelper::indexSanityCheck('state', $address),
                            'PostalCode' => IndexSanityCheckHelper::indexSanityCheck('postal_code', $address)
                        ];
                    break;
                case 'BILLING':
                    $contact['BillAddr'] =
                        [
                            'Line1' => IndexSanityCheckHelper::indexSanityCheck('address_line_1', $address),
                            'City' => IndexSanityCheckHelper::indexSanityCheck('city', $address),
                            'Country' => IndexSanityCheckHelper::indexSanityCheck('country', $address),
                            'CountrySubDivisionCode' => IndexSanityCheckHelper::indexSanityCheck('state', $address),
                            'PostalCode' => IndexSanityCheckHelper::indexSanityCheck('postal_code', $address)
                        ];
                    break;
                default:
                    $contact['OtherAddr'] =
                        [
                            'Line1' => IndexSanityCheckHelper::indexSanityCheck('address_line_1', $address),
                            'City' => IndexSanityCheckHelper::indexSanityCheck('city', $address),
                            'Country' => IndexSanityCheckHelper::indexSanityCheck('country', $address),
                            'CountrySubDivisionCode' => IndexSanityCheckHelper::indexSanityCheck('state', $address),
                            'PostalCode' => IndexSanityCheckHelper::indexSanityCheck('postal_code', $address)
                        ];
                    break;
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
                    $contact['PrimaryPhone'] =
                        [
                            'FreeFormNumber' => IndexSanityCheckHelper::indexSanityCheck('country_code', $phone) . ' ' .
                                IndexSanityCheckHelper::indexSanityCheck('area_code', $phone). ' '.
                                IndexSanityCheckHelper::indexSanityCheck('phone_number', $phone)
                        ];
                    break;
                case 'MOBILE':
                    $contact['Mobile'] =
                        [
                            'FreeFormNumber' => IndexSanityCheckHelper::indexSanityCheck('country_code', $phone) . ' ' .
                                IndexSanityCheckHelper::indexSanityCheck('area_code', $phone). ' '.
                                IndexSanityCheckHelper::indexSanityCheck('phone_number', $phone)
                        ];
                    break;
                default:
                    if (!array_key_exists('AlternatePhone', $contact)) {
                        $contact['AlternatePhone'] =
                            [
                                'FreeFormNumber' => IndexSanityCheckHelper::indexSanityCheck('country_code', $phone) . ' ' .
                                    IndexSanityCheckHelper::indexSanityCheck('area_code', $phone). ' '.
                                    IndexSanityCheckHelper::indexSanityCheck('phone_number', $phone)
                            ];
                        break;
                    }
                    break;
            }
        }
        return $contact;
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        try {
            $this->validate('name');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('DisplayName', 'name');
        $this->issetParam('GivenName', 'first_name');
        $this->issetParam('FamilyName', 'last_name');

        if ($this->getStatus()) {
            if ($this->getStatus() == 'ACTIVE') {
                $this->data['Active'] = true;
            } elseif ($this->getStatus() == 'INACTIVE') {
                $this->data['Active'] = false;
            }
        }

        if ($this->getEmailAddress()) {
            $this->data['PrimaryEmailAddr'] = [
                'Address' => $this->getEmailAddress()
            ];
        }

        if ($this->getWebsite()) {
            $this->data['WebAddr'] = [
                'URI' => $this->getWebsite()
            ];
        }

        if ($this->getPhones()) {
            $this->data = $this->getPhoneData($this->getPhones(), $this->data);
        }

        if ($this->getAddresses()) {
            $this->data = $this->getAddressData($this->getAddresses(), $this->data);
        }
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateContactResponse
     * @throws \Exception
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = [
                'status' => 'error',
                'type' => 'InvalidRequestException',
                'detail' =>
                    [
                        'message' => $data->getMessage(),
                        'error_code' => $data->getCode(),
                        'status_code' => 422,
                    ],
            ];
            return $this->createResponse($response);
        }

        $quickbooks = $this->createQuickbooksDataService();
        $createParams = [];

        foreach ($data as $key => $value){
            $createParams[$key] = $data[$key];
        }

        $account = Customer::create($createParams);
        $response = $quickbooks->Add($account);
        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }

        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return CreateContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateContactResponse($this, $data);
    }

}