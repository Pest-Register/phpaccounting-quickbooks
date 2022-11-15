<?php


namespace PHPAccounting\Quickbooks\Message\Quotations\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Quickbooks\Helpers\ErrorParsingHelper;
use PHPAccounting\Quickbooks\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Quickbooks\Message\AbstractQuickbooksRequest;
use PHPAccounting\Quickbooks\Message\Quotations\Requests\Traits\QuotationRequestTrait;
use PHPAccounting\Quickbooks\Message\Quotations\Responses\UpdateQuotationResponse;
use QuickBooksOnline\API\Facades\Estimate;

class UpdateQuotationRequest extends AbstractQuickbooksRequest
{
    use QuotationRequestTrait;

    public string $model = 'Quotation';

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
            $this->validate('contact', 'quotation_data', 'accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('Id', 'accounting_id');
        $this->issetParam('TxnDate', 'date');
        $this->issetParam('ExpirationDate', 'expiry_date');
        $this->issetParam('AcceptedDate', 'accepted_date');
        $this->issetParam('DocNumber', 'quotation_number');
        $this->issetParam('SyncToken', 'sync_token');
        $this->issetParam('TxnStatus', 'status');


        if ($this->getQuotationData()) {
            $this->data['Line'] = $this->addLineItemsToQuotation($this->getQuotationData());
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
     * @return \Omnipay\Common\Message\ResponseInterface|UpdateQuotationResponse
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
            $targetItem = $quickbooks->Query("select * from Estimate where Id='".$id."'");
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
                $item = Estimate::update(current($targetItem), $updateParams);
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
                                'message' => 'Existing Estimate not found',
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
     * @return UpdateQuotationResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateQuotationResponse($this, $data);
    }

}
