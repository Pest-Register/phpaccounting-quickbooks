<?php
namespace PHPAccounting\Quickbooks\Message;

use QuickBooksOnline\API\DataService\DataService;
use XeroPHP\Remote\Exception\RateLimitExceededException;

abstract class AbstractQuickbooksRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $quickbooksDataService;

    protected $data = [];

    abstract public function sendData($data);

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    abstract public function getData();

    /**
     * ClientID getters and setters
     * @return mixed
     */
    public function getClientID(){
        return $this->getParameter('clientID');
    }

    public function setClientID($value) {
        return $this->setParameter('clientID', $value);
    }

    /**
     * ClientSecret getters and setters
     * @return mixed
     */
    public function getClientSecret(){
        return $this->getParameter('clientSecret');
    }

    public function setClientSecret($value) {
        return $this->setParameter('clientSecret', $value);
    }

    /**
     * Access Token getters and setters
     * @return mixed
     */

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    /**
     * RefreshToken getters and setters
     * @return mixed
     */
    public function getRefreshToken(){
        return $this->getParameter('refreshToken');
    }

    public function setRefreshToken($value) {
        return $this->setParameter('refreshToken', $value);
    }

    /**
     * QBORealmID getters and setters
     * @return mixed
     */
    public function getQBORealmID(){
        return $this->getParameter('qboRealmID');
    }

    public function setQBORealmID($value) {
        return $this->setParameter('qboRealmID', $value);
    }

    /**
     * BaseURL getters and setters
     * @return mixed
     */
    public function getBaseURL(){
        return $this->getParameter('baseURL');
    }

    public function setBaseURL($value) {
        return $this->setParameter('baseURL', $value);
    }

    protected function createQuickbooksDataService(){
        try {
            $quickbooksDataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $this->getClientID(),
                'ClientSecret' => $this->getClientSecret(),
                'accessTokenKey' => $this->getAccessToken(),
                'refreshTokenKey' => $this->getRefreshToken(),
                'QBORealmID' => $this->getQBORealmID(),
                'baseUrl' => $this->getBaseURL()
            ));
            $this->quickbooksDataService = $quickbooksDataService;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        return $this->quickbooksDataService;
    }

    public function getQuickbooksDataService(){
        return $this->quickbooksDataService;
    }

    /**
     * Handle exception messages, codes and additional details
     * @param $exception
     * @param $type
     * @return array
     */
    public function handleRequestException($exception, $type): array
    {
        $response = [
            'status' => 'error',
            'type' => $type,
            'detail' => $exception->getMessage(),
            'error_code' => $exception->getCode(),
            'status_code' => $exception->getCode(),
        ];
        return $response;
    }

    /**
     * Check if key exists in param bag and add it to array
     * @param $QuickbooksKey
     * @param $localKey
     */
    public function issetParam($quickbooksKey, $localKey){
        if(array_key_exists($localKey, $this->getParameters())){
            $this->data[$quickbooksKey] = $this->getParameter($localKey);
        }
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }
}