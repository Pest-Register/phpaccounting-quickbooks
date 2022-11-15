<?php

namespace PHPAccounting\Quickbooks\Message\Payments\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Payments\Requests\Traits\PaymentRequestTrait;
use PHPAccounting\Quickbooks\Message\Payments\Responses\CreatePaymentResponse;
use QuickBooksOnline\API\Facades\Payment;

/**
 * Create Invoice
 * @package PHPAccounting\Quickbooks\Message\Invoices\Requests
 */
class CreatePaymentRequest extends AbstractQuickbooksRequest
{
    use PaymentRequestTrait;

    public string $model = 'Payment';


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
            $this->validate('amount', 'date');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('TotalAmt', 'amount');
        $this->issetParam('PaymentRefNum', 'reference_id');
        $this->issetParam('SyncToken', 'sync_token');
        $this->data['Line'] = [];
        if ($this->getAmount()) {
            if ($this->getInvoice()) {
                $this->data = $this->addInvoiceToPayment($this->data, $this->getInvoice(), $this->getAmount());
            }

//            if ($this->getCreditNote()) {
//                $this->data = $this->addCreditNoteToPayment($this->data, $this->getCreditNote(), $this->getAmount());
//            }
        }

        if ($this->getAccount()) {
            $this->data['ARAccountRef']['value'] = $this->getAccount()['accounting_id'];
            $this->data['DepositToAccountRef']['value'] = $this->getAccount()['accounting_id'];
        }

        if ($this->getCurrency()) {
            $this->data['CurrencyRef']['value'] = $this->getCurrency();
        }

        if ($this->getContact()) {
            $this->data['CustomerRef']['value'] = $this->getContact()['accounting_id'];
        }
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreatePaymentResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
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
            $payment = Payment::create($createParams);
            $response = $quickbooks->Add($payment);

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
     * @return CreatePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreatePaymentResponse($this, $data);
    }


}
