<?php


namespace PHPAccounting\Quickbooks\Message\Payments\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Payments\Requests\Traits\PaymentRequestTrait;
use PHPAccounting\Quickbooks\Message\Payments\Responses\UpdatePaymentResponse;
use QuickBooksOnline\API\Facades\Payment;

class UpdatePaymentRequest extends AbstractQuickbooksRequest
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
            $this->validate('accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('TotalAmt', 'amount');
        $this->issetParam('PaymentRefNum', 'reference_id');
        $this->issetParam('SyncToken', 'sync_token');

        $this->data['sparse'] = true;

        $this->data['Line'] = [];
        if ($this->getAmount()) {
            if ($this->getInvoice()) {
                $this->data = $this->addInvoiceToPayment($this->data, $this->getInvoice(), $this->getAmount());
            }

//            if ($this->getCreditNote()) {
//                $this->data= $this->addCreditNoteToPayment($this->data, $this->getCreditNote(), $this->getAmount());
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

        if ($this->getDate()) {
            $this->data['TxnDate'] = $this->getDate();
        }
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|UpdatePaymentResponse
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
        $updateParams = [];

        foreach ($data as $key => $value){
            $updateParams[$key] = $data[$key];
        }

        $id = $this->getAccountingID();
        try {
            $targetItem = $quickbooks->Query("select * from Payment where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                'status' => 'error',
                'type' => 'InvalidRequestException',
                'detail' =>
                    [
                        'message' => $exception->getMessage(),
                        'error_code' => null,
                        'status_code' => 422,
                    ],
            ]);
        }

        try {
            if (!empty($targetItem) && sizeof($targetItem) == 1) {
                $item = Payment::update(current($targetItem), $updateParams);
                $response = $quickbooks->Update($item);
            } else {
                $error = $quickbooks->getLastError();
                if ($error) {
                    $response = ErrorParsingHelper::parseError($error);
                } else {
                    return $this->createResponse([
                        'status' => 'error',
                        'type' => 'InvalidRequestException',
                        'detail' =>
                            [
                                'message' => 'Existing Payment not found',
                                'error_code' => null,
                                'status_code' => 422,
                            ],
                    ]);
                }
            }


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
     * @return UpdatePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdatePaymentResponse($this, $data);
    }
}
