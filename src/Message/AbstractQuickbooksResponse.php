<?php
namespace PHPAccounting\Quickbooks\Message;

use PHPAccounting\Quickbooks\Foundation\Contracts\RequestInterface;
use PHPAccounting\Quickbooks\Helpers\ErrorResponseHelper;


/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 3:33 PM
 */

class AbstractQuickbooksResponse extends \PHPAccounting\Quickbooks\Foundation\AbstractResponse
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
            $this->data['error']['status'] ?? $this->data['status'] ?? null,
            $detail['error_code'] ?? null,
            $detail['status_code'] ?? null,
            $detail['detail'] ?? null,
            $this->modelType);
    }

    /**
     * Handle exception messages, codes and additional details
     */
    public function getErrorMessage(){
        // Surface 429 as a structured rate-limit error so
        // AccountingException::handle dispatches to RateLimitException and
        // Mira's handleRateLimit honours Retry-After instead of falling back
        // to the default 120s sleep.
        if ($this->isRateLimited()) {
            return $this->rateLimitErrorPayload();
        }

        if ($this->data) {
            if (isset($this->data['error'])) {
                if (isset($this->data['error']['status'])){
                    return $this->parseErrorMessage();
                }
            } elseif (isset($this->data['status'])) {
                return $this->parseErrorMessage();
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
     * Did Intuit reject this call with a 429 (Too Many Requests)? QB returns
     * the status either as the HTTP-level code on the FaultHandler (captured
     * via ErrorParsingHelper into `data.error.status`) or — for SDK-internal
     * rate handling — directly under `data.status`.
     */
    private function isRateLimited(): bool
    {
        $candidates = [
            $this->data['error']['status'] ?? null,
            $this->data['status'] ?? null,
        ];
        foreach ($candidates as $code) {
            if ((int) $code === 429) {
                return true;
            }
        }
        return false;
    }

    /**
     * Build the rate-limit payload shape that AccountingException::handle
     * matches on (the message substring) and that
     * GenericProviderInteractions::handleRateLimit consumes
     * (rate_problem + retry).
     */
    private function rateLimitErrorPayload(): array
    {
        return [
            'message' => 'The API rate limit for your organisation/application pairing has been exceeded',
            'exception' => 'Rate limit exceeded',
            'rate_problem' => 'minute',
            'retry' => $this->extractRetryAfterSeconds() ?? 60,
            'error_code' => 429,
            'status_code' => 429,
            'detail' => null,
        ];
    }

    /**
     * Read the Retry-After response header (case-insensitive). Intuit
     * returns either a seconds count or an HTTP-date; we treat anything
     * non-numeric as "unknown" and fall through to the default backoff.
     */
    private function extractRetryAfterSeconds(): ?int
    {
        if (! is_array($this->headers)) {
            return null;
        }
        foreach ($this->headers as $name => $value) {
            if (strcasecmp((string) $name, 'retry-after') !== 0) {
                continue;
            }
            if (is_array($value)) {
                $value = $value[0] ?? null;
            }
            if ($value === null || $value === '') {
                continue;
            }
            return is_numeric($value) ? (int) $value : null;
        }
        return null;
    }

    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful(): bool
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

    /**
     * Return Intuit's request-trace id ("intuit-tid") from the response
     * headers when present. This is the identifier Intuit support asks for
     * when escalating an API issue — capturing it on the Mira side via the
     * RequestContext lets us cite it in support tickets without re-running
     * the call.
     *
     * @return string|null
     */
    public function getTraceId()
    {
        if (! is_array($this->headers) || empty($this->headers)) {
            return null;
        }

        // Headers may arrive as either PSR-7 arrays-of-strings or as flat
        // string maps; tolerate both. Names are case-insensitive.
        foreach ($this->headers as $name => $value) {
            if (strcasecmp((string) $name, 'intuit-tid') !== 0
                && strcasecmp((string) $name, 'intuit_tid') !== 0) {
                continue;
            }
            if (is_array($value)) {
                $value = $value[0] ?? null;
            }
            if ($value !== null && $value !== '') {
                return (string) $value;
            }
        }
        return null;
    }
}