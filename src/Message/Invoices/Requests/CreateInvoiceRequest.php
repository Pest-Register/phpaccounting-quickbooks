<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Invoices\Requests\Traits\InvoiceRequestTrait;
use PHPAccounting\Quickbooks\Message\Invoices\Responses\CreateInvoiceResponse;
use QuickBooksOnline\API\Facades\Invoice;

/**
 * Create Invoice
 * @package PHPAccounting\Quickbooks\Message\Invoices\Requests
 */
class CreateInvoiceRequest extends AbstractQuickbooksRequest
{
    use InvoiceRequestTrait;

    public string $model = 'Invoice';

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
            $this->validate('type', 'contact', 'invoice_data');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('TxnDate', 'date');
        $this->issetParam('DueDate', 'due_date');
        $this->issetParam('DocNumber', 'invoice_number');
        $this->issetParam('Deposit', 'deposit_amount');
        $this->issetParam('SyncToken', 'sync_token');

        if ($this->getInvoiceData()) {
            $this->data['Line'] = $this->addLineItemsToInvoice($this->getInvoiceData());
        }

        if ($this->getDepositAccount()) {
            $this->data['DepositToAccountRef']['value'] = $this->getDepositAccount();
        }

        if ($this->getContact()) {
            $this->data['CustomerRef'] = [
                'value' => $this->getContact()
            ];
        }

        if ($this->getGSTInclusive()) {
            if ($this->getGSTInclusive() === 'EXCLUSIVE') {
                $this->data['GlobalTaxCalculation'] = 'TaxExcluded';
            }
            if ($this->getGSTInclusive() === 'INCLUSIVE') {
                $this->data['GlobalTaxCalculation'] = 'TaxInclusive';
            }
            if ($this->getGSTInclusive() === 'NONE') {
                $this->data['GlobalTaxCalculation'] = 'NotApplicable';
            }
        }

        if ($this->getStatus()) {
            if ($this->getStatus() === 'OPEN') {
                $this->data['EmailStatus'] = 'EmailSent';
            } else {
                $this->data['EmailStatus'] = 'NotSet';
            }
        }

        if ($this->getTotalTax() ) {
            $this->data['TxnTaxDetail'] = [];
            $this->data['TxnTaxDetail']['TaxLine'] = [];
            $this->data['TxnTaxDetail']['TotalTax'] = $this->getTotalTax();
            if ($this->getTaxLines()) {
                foreach ($this->getTaxLines() as $key => $value) {
                    $taxLineItem = [];
                    $taxLineItem['DetailType'] = 'TaxLineDetail';
                    $taxLineItem['Amount'] = $value['total_tax'];
                    $taxLineItem['TaxLineDetail'] = [];
                    $taxLineItem['TaxLineDetail']['TaxRateRef'] = $value['tax_rate_id'];
                    $taxLineItem['TaxLineDetail']['TaxPercent'] = $value['tax_percent'];
                    $taxLineItem['TaxLineDetail']['NetAmountTaxable'] = $value['net_amount'];
                    $taxLineItem['TaxLineDetail']['PercentBased'] = true;
                    array_push($this->data['TxnTaxDetail']['TaxLine'],$taxLineItem);
                }
            }
        }

        if ($this->getAddress()) {
            $address = $this->getAddress();
            $this->data['BillAddr'] =
                [
                    'Line1' => IndexSanityCheckHelper::indexSanityCheck('address_line_1', $address),
                    'City' => IndexSanityCheckHelper::indexSanityCheck('city', $address),
                    'Country' => IndexSanityCheckHelper::indexSanityCheck('country', $address),
                    'PostalCode' => IndexSanityCheckHelper::indexSanityCheck('postal_code', $address)
                ];
        }
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateInvoiceResponse
     * @throws \Exception
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            return $this->createResponse(
                $this->handleRequestException($data, 'InvalidRequestException')
            );
        }
        $quickbooks = $this->createQuickbooksDataService();
        $createParams = [];

        foreach ($data as $key => $value){
            $createParams[$key] = $data[$key];
        }

        try {
            $account = Invoice::create($createParams);
            $response = $quickbooks->Add($account);
            $error = $quickbooks->getLastError();
            if ($error) {
                $response = ErrorParsingHelper::parseError($error);
            }
        }
        catch (\Throwable $exception) {
            $response = ErrorParsingHelper::parseQbPackageError($exception);
        }

        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return CreateInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateInvoiceResponse($this, $data);
    }


}
