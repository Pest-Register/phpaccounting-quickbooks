<?php

namespace PHPAccounting\Quickbooks\Message\Invoices\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Invoices\Responses\DeleteInvoiceResponse;
use PHPAccounting\Quickbooks\Traits\AccountingIDRequestTrait;
use QuickBooksOnline\API\Facades\Invoice;

/**
 * Delete Invoice
 * @package PHPAccounting\Quickbooks\Message\Invoices\Requests
 */
class DeleteInvoiceRequest extends AbstractQuickbooksRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'Invoice';

    /**
     * Get Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @return mixed
     */
    public function getSynToken(){
        return $this->getParameter('sync_token');
    }

    /**
     * Set Sync Token Parameter from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/invoices
     * @param string $value Deposit Amount
     * @return DeleteInvoiceRequest
     */
    public function setSyncToken($value){
        return $this->setParameter('sync_token', $value);
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
            $this->validate('accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }
        $this->issetParam('Id', 'accounting_id');
        $this->issetParam('SyncToken', 'sync_token');

//        $this->data['Active'] = false;
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return DeleteInvoiceResponse
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
            $targetAccount = $quickbooks->Query("select * from Invoice where Id='".$id."'");
        } catch (\Exception $exception) {
            return $this->createResponse([
                [
                    'status' => 'error',
                    'type' => 'InvalidRequestException',
                    'detail' =>
                        [
                            'message' => $exception->getMessage(),
                            'error_code' => $exception->getCode(),
                            'status_code' => 422,
                        ],
                ]
            ]);
        };

        try {
            if (!empty($targetAccount) && sizeof($targetAccount) == 1) {
                $invoice = Invoice::update(current($targetAccount), $updateParams);
                $response = $quickbooks->Delete($invoice);
            } else {
                return $this->createResponse([
                    'status' => 'error',
                    'type' => 'InvalidRequestException',
                    'detail' =>
                        [
                            'message' => 'Existing Invoice not found',
                            'error_code' => null,
                            'status_code' => 422,
                        ],
                ]);
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
     * @return DeleteInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteInvoiceResponse($this, $data);
    }
}
