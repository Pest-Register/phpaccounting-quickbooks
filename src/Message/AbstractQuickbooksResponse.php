<?php
namespace PHPAccounting\Quickbooks\Message;

use Omnipay\Common\Message\RequestInterface;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;


/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 3:33 PM
 */

class AbstractQuickbooksResponse extends \Omnipay\Common\Message\AbstractResponse
{
    /**
     * Model type used for abstract parsing of errors and responses
     * @var string
     */
    private string $modelType;

    /**
     * Requests id
     *
     * @var string URL
     */
    protected $requestId = null;
    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Sets model type, request interface and data model
     * @param RequestInterface $request
     * @param $data
     */
    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        $this->request = $request;
        if (is_string($data)) {
            $this->data = json_decode($data, true);
        } else {
            $this->data = $data;
        }
        $this->headers = $headers;
        $this->modelType = $request->model;
        parent::__construct($request, $data);
    }

    /**
     * Parse error message from provider
     * @return array
     */
    private function parseErrorMessage() {
        $detail = $this->data['error']['detail'] ?? [];
        return ErrorResponseHelper::parseErrorResponse(
            $detail['message'] ?? null,
            $this->data['error']['status'],
            $detail['error_code'] ?? null,
            $detail['status_code'] ?? null,
            $detail['detail'] ?? null,
            $this->modelType);
    }

    /**
     * Handle exception messages, codes and additional details
     */
    public function getErrorMessage(){
        if ($this->data) {
            if (array_key_exists('error', $this->data)) {
                if ($this->data['error']['status']){
                    $this->parseErrorMessage();
                }
            } elseif (array_key_exists('status', $this->data)) {
                $this->parseErrorMessage();
            }
        } else {
            return [
                'message' => 'NULL Returned from API or End of Pagination',
                'exception' =>'NULL Returned from API or End of Pagination',
                'error_code' => null,
                'status_code' => null,
                'detail' => null
            ];
        }

        return null;
    }

    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if (is_object($this->data)) {
                if (property_exists($this->data, 'status')) {
                    if ($this->data->status == 'error') {
                        return false;
                    }
                }
                if (property_exists($this->data,'error')) {
                    if ($this->data->error->status){
                        return false;
                    }
                }
            }
            elseif (is_array($this->data)) {
                if (array_key_exists('status', $this->data)) {
                    if ($this->data['status'] == 'error') {
                        return false;
                    }
                }
                if (array_key_exists('error', $this->data)) {
                    if ($this->data['error']['status']){
                        return false;
                    }
                }
            }
        } else {
            return false;
        }

        return true;
    }

    public function getHeaders(){
        return $this->headers;
    }
}