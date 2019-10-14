<?php
namespace PHPAccounting\Quickbooks;

use Omnipay\Common\AbstractGateway;

/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 13/05/2019
 * Time: 3:11 PM
 * @method \PhpAccounting\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface authorize(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface capture(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface purchase(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface refund(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \PhpAccounting\Common\Message\RequestInterface void(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface createCard(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface deleteCard(array $options = array())
 */

class Gateway extends AbstractGateway
{
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     * @return string
     */
    public function getName()
    {
        return 'Quickbooks';
    }

    /**
     * ClientID getters and setters
     * @return mixed
     */
    public function getClientID(){
        return $this->getParameter('clientID');
    }

    public function setClientID($value) {
        return $this->setParameter('clientID', $value);
    }

    /**
     * ClientSecret getters and setters
     * @return mixed
     */
    public function getClientSecret(){
        return $this->getParameter('clientSecret');
    }

    public function setClientSecret($value) {
        return $this->setParameter('clientSecret', $value);
    }

    /**
     * Access Token getters and setters
     * @return mixed
     */

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    /**
     * RefreshToken getters and setters
     * @return mixed
     */
    public function getRefreshToken(){
        return $this->getParameter('refreshToken');
    }

    public function setRefreshToken($value) {
        return $this->setParameter('refreshToken', $value);
    }

    /**
     * QBORealmID getters and setters
     * @return mixed
     */
    public function getQBORealmID(){
        return $this->getParameter('qboRealmID');
    }

    public function setQBORealmID($value) {
        return $this->setParameter('qboRealmID', $value);
    }

    /**
     * BaseURL getters and setters
     * @return mixed
     */
    public function getBaseURL(){
        return $this->getParameter('baseURL');
    }

    public function setBaseURL($value) {
        return $this->setParameter('baseURL', $value);
    }


    /**
     * Customer Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */



    /**
     * Get One or Multiple Contacts
     * @param array $parameters
     * @bodyParam array $parameters
     * @bodyParam parameters.page int optional Page Index for Pagination
     * @bodyParam parameters.accountingIDs array optional Array of GUIDs for Contact Retrieval / Filtration
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function createContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Contacts\Requests\CreateContactRequest', $parameters);
    }

    public function updateContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Contacts\Requests\UpdateContactRequest', $parameters);
    }

    public function getContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Contacts\Requests\GetContactRequest', $parameters);
    }

    public function deleteContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Contacts\Requests\DeleteContactRequest', $parameters);
    }


    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createContactGroup(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\ContactGroups\Requests\CreateContactGroupRequest', $parameters);
    }

    public function updateContactGroup(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\ContactGroups\Requests\UpdateContactGroupRequest', $parameters);
    }

    public function getContactGroup(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\ContactGroups\Requests\GetContactGroupRequest', $parameters);
    }

    public function deleteContactGroup(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\ContactGroups\Requests\DeleteContactGroupRequest', $parameters);
    }


    /**
     * Invoice Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createInvoice(array $parameters = []){
            return $this->createRequest('\PHPAccounting\Quickbooks\Message\Invoices\Requests\CreateInvoiceRequest', $parameters);
    }

    public function updateInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Invoices\Requests\UpdateInvoiceRequest', $parameters);
    }

    public function getInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Invoices\Requests\GetInvoiceRequest', $parameters);
    }

    public function deleteInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Invoices\Requests\DeleteInvoiceRequest', $parameters);
    }

    /**
     * Account Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createAccount(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Accounts\Requests\CreateAccountRequest', $parameters);
    }

    public function updateAccount(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Accounts\Requests\UpdateAccountRequest', $parameters);
    }

    public function getAccount(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Accounts\Requests\GetAccountRequest', $parameters);
    }

    public function deleteAccount(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Accounts\Requests\DeleteAccountRequest', $parameters);
    }

    /**
     * Payment Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createPayment(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Payments\Requests\CreatePaymentRequest', $parameters);
    }

    public function updatePayment(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Payments\Requests\UpdatePaymentRequest', $parameters);
    }

    public function getPayment(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Payments\Requests\GetPaymentRequest', $parameters);
    }

    public function deletePayment(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Payments\Requests\DeletePaymentRequest', $parameters);
    }

    /**
     * Organisation Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function getOrganisation(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Organisations\Requests\GetOrganisationRequest', $parameters);
    }

    /**
     * Inventory Item Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createInventoryItem(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\InventoryItems\Requests\CreateInventoryItemRequest', $parameters);
    }

    public function updateInventoryItem(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\InventoryItems\Requests\UpdateInventoryItemRequest', $parameters);
    }

    public function getInventoryItem(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\InventoryItems\Requests\GetInventoryItemRequest', $parameters);
    }

    public function deleteInventoryItem(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\InventoryItems\Requests\DeleteInventoryItemRequest', $parameters);
    }

    /**
     * Tax Rates Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createTaxRate(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\TaxRates\Requests\CreateTaxRateRequest', $parameters);
    }

    public function updateTaxRate(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\TaxRates\Requests\UpdateTaxRateRequest', $parameters);
    }

    public function getTaxRate(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\TaxRates\Requests\GetTaxRateRequest', $parameters);
    }

    public function deleteTaxRate(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\TaxRates\Requests\DeleteTaxRateRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getTaxRateValue(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\TaxRateValues\Requests\GetTaxRateValuesRequest', $parameters);
    }

    /**
     * Journal Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getJournal(array $parameters = []) {
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\Journals\Requests\GetJournalRequest', $parameters);
    }

    /**
     * Manual Journal Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getManualJournal(array $parameters = []) {
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\ManualJournals\Requests\GetManualJournalRequest', $parameters);
    }

    public function createManualJournal(array $parameters = []) {
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\ManualJournals\Requests\CreateManualJournalRequest', $parameters);
    }

    public function updateManualJournal(array $parameters = []) {
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\ManualJournals\Requests\CreateManualJournalRequest', $parameters);
    }

    public function deleteManualJournal(array $parameters = []) {
        return $this->createRequest('\PHPAccounting\Quickbooks\Message\ManualJournals\Requests\DeleteManualJournalRequest', $parameters);
    }

}