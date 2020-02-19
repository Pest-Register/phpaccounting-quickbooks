<?php


namespace PHPAccounting\Quickbooks\Message\Payments\Requests;


use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractRequest;
use PHPAccounting\Quickbooks\Message\Payments\Responses\DeletePaymentResponse;

class DeletePaymentRequest extends AbstractRequest
{
    /**
     * Set Journal Data Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/payment
     * @param string $value Status
     * @return DeletePaymentRequest
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
    }

    /**
     * Get Journal Data Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/payment
     * @return mixed
     */
    public function getSyncToken(){
        return $this->getParameter('sync_token');
    }

    /**
     * Set AccountingID from Parameter Bag (PaymentID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/payment
     * @param $value
     * @return DeletePaymentRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (PaymentID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/payment
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
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
        $this->validate('accounting_id', 'sync_token');

        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return DeletePaymentResponse
     * @throws \QuickBooksOnline\API\Exception\IdsException
     */
    public function sendData($data)
    {
        $quickbooks = $this->createQuickbooksDataService();
        $payment = $quickbooks->FindbyId('payment', $this->getAccountingID());
        $response = $quickbooks->Delete($payment);
        $error = $quickbooks->getLastError();
        if ($error) {
            $response = ErrorParsingHelper::parseError($error);
        }

        return $this->createResponse($response);
    }


    /**
     * Create Generic Response from Quickbooks Endpoint
     * @param mixed $data Array Elements or Quickbooks Collection from Response
     * @return DeletePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeletePaymentResponse($this, $data);
    }
}