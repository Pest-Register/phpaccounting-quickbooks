<?php


namespace PHPAccounting\Quickbooks\Message\Payments\Requests;


use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Payments\Responses\DeletePaymentResponse;
use PHPAccounting\Quickbooks\Traits\AccountingIDRequestTrait;

class DeletePaymentRequest extends AbstractQuickbooksRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'Payment';

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
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        try {
            $this->validate('accounting_id', 'sync_token');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

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