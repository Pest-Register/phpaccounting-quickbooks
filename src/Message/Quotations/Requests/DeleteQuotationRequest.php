<?php


namespace PHPAccounting\Quickbooks\Message\Quotations\Requests;


use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Quotations\Responses\GetQuotationResponse;
use PHPAccounting\Quickbooks\Traits\AccountingIDRequestTrait;
use QuickBooksOnline\API\Facades\Estimate;

class DeleteQuotationRequest extends AbstractQuickbooksRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'Quotation';

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
     * @return DeleteQuotationRequest
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
        $this->data['TxnStatus'] = 'Closed';
        return $this->data;
    }

    /**
     * Send Data to Quickbooks Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetQuotationResponse
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
            $targetCustomer = $quickbooks->Query("select * from Estimate where Id='".$id."'");
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
            if (!empty($targetCustomer) && sizeof($targetCustomer) == 1) {
                $estimate = Estimate::update(current($targetCustomer), $updateParams);
                $response = $quickbooks->Update($estimate);
            } else {
                return $this->createResponse([
                    'status' => 'error',
                    'type' => 'InvalidRequestException',
                    'detail' =>
                        [
                            'message' => 'Existing Estimate not found',
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
     * @return GetQuotationResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetQuotationResponse($this, $data);
    }
}
