<?php
/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 11/16/2015
 * Time: 9:49 AM
 */

/**
 * Class QSoft_SocialConnect_Model_Vk_Oauth2_Client
 */
class QSoft_SocialConnect_Model_Vk_Oauth2_Client
{

    const REDIRECT_URI_ROUTE = 'socialconnect/vk/connect';

    const XML_PATH_ENABLED = 'social_cfg/qsoft_socialconnect_vk/enabled';
    const XML_PATH_CLIENT_ID = 'social_cfg/qsoft_socialconnect_vk/client_id';
    const XML_PATH_CLIENT_SECRET = 'social_cfg/qsoft_socialconnect_vk/client_secret';

    const OAUTH2_AUTH_URI = 'https://oauth.vk.com/authorize';
    const OAUTH2_TOKEN_URI = 'https://oauth.vk.com/access_token';
    const API_SERVICE_URI = 'https://api.vk.com/method/';

    /**
     * Standard redirect uri
     */
    const REDIRECT_URI_BLANK = 'https://oauth.vk.com/blank.html';

    /**
     * Application server authorization grant type
     */
    const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';


    protected $clientId = null;
    protected $clientSecret = null;
    protected $redirectUri = self::REDIRECT_URI_BLANK;
    protected $scope = array('email');

    protected $token = null;

    public function __construct($params = array())
    {
        if (($this->isEnabled = $this->_isEnabled())) {
            $this->clientId = $this->_getClientId();
            $this->clientSecret = $this->_getClientSecret();
            $this->redirectUri = Mage::getModel('core/url')->sessionUrlVar(
                Mage::getUrl(self::REDIRECT_URI_ROUTE)
            );

            if (!empty($params['scope'])) {
                $this->scope = $params['scope'];
            }

        }
    }

    public function isEnabled()
    {
        return (bool)$this->isEnabled;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    public function getScope()
    {
        return $this->scope;
    }


    public function setAccessToken($token)
    {
        $this->token = $token;
    }


    public function getAccessToken($code = null)
    {
        if (!empty($code)) {
            return $this->fetchAccessToken($code);
        } else if (!empty($this->token)) {
            return $this->token;
        } else {
            throw new Exception(
                Mage::helper('qsoft_socialconnect')
                    ->__('Unable to proceed without an access token.')
            );
        }
    }

    /**
     * Create OAuth Authorization Connection
     *
     * @return string
     */
    public function createAuthUrl()
    {
        $url = self::OAUTH2_AUTH_URI . '?' .
            http_build_query(
                array(
                    'client_id' => $this->clientId,
                    'scope' => implode(',', $this->scope),
                    'redirect_uri' => $this->redirectUri,
                    'response_type' => 'code'
                )
            );
        return $url;
    }

    /**
     * Receiving access_token
     *
     * @param $code
     * @return mixed|null
     * @throws Exception
     * @throws QSoft_SocialConnect_Model_Vk_Oauth2_Exception
     */
    protected function fetchAccessToken($code)
    {
        $response = $this->_httpRequest(
            self::OAUTH2_TOKEN_URI,
            'POST',
            array(
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->redirectUri
            )
        );

        $this->token = $response;
        return $this->token;
    }

    /**
     * Get $response from API Request
     *
     * @param $endpoint
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function api($endpoint, $method = 'GET', $params = array())
    {
        if (empty($this->token)) {
            throw new Exception(
                Mage::helper('qsoft_socialconnect')
                    ->__('Unable to proceed without an access token.')
            );
        }

        $url = self::API_SERVICE_URI . $endpoint . '?' . 'fields=' . implode(',', $params) . '&access_token' . $this->token->access_token;

        $method = strtoupper($method);

        $params = array_merge(array(
            'access_token' => $this->token->access_token
        ), $params);

        $response = $this->_httpRequest($url, $method, $params);

        $response = $response->response[0];

        return $response;
    }


    /**
     * Make API requests.
     *
     * @param $url
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws Exception
     * @throws QSoft_SocialConnect_Model_Vk_Oauth2_Exception
     * @throws Zend_Http_Client_Exception
     */
    protected function _httpRequest($url, $method = 'GET', $params = array())
    {
        $client = new Zend_Http_Client($url, array('timeout' => 60));

        switch ($method) {
            case 'GET':
                $client->setParameterGet($params);
                break;
            case 'POST':
                $client->setParameterPost($params);
                break;
            case 'DELETE':
                $client->setParameterGet($params);
                break;
            default:
                throw new Exception(
                    Mage::helper('qsoft_socialconnect')
                        ->__('Required HTTP method is not supported.')
                );
        }

        $response = $client->request($method);


        QSoft_SocialConnect_Helper_Data::log($response->getStatus() . ' - ' . $response->getBody());

        $decodedResponse = json_decode($response->getBody());

        /*
         * Vk should return data using the "application/json" media type.
         * Vk violates OAuth2 specification and returns string. If this
         * ever gets fixed, following condition will not be used anymore.
         */
        if (empty($decodedResponse)) {
            $parsed_response = array();
            parse_str($response->getBody(), $parsed_response);

            $decodedResponse = json_decode(json_encode($parsed_response));
        }

        if ($response->isError()) {
            $status = $response->getStatus();
            if (($status == 400 || $status == 401)) {
                if (isset($decodedResponse->error->message)) {
                    $message = $decodedResponse->error->message;
                } else {
                    $message = Mage::helper('qsoft_socialconnect')
                        ->__('Unspecified OAuth error occurred.');
                }

                throw new QSoft_SocialConnect_Model_Vk_Oauth2_Exception($message);
            } else {
                $message = sprintf(
                    Mage::helper('qsoft_socialconnect')
                        ->__('HTTP error %d occurred while issuing request.'),
                    $status
                );

                throw new Exception($message);
            }
        }

        return $decodedResponse;
    }

    protected function _isEnabled()
    {
        return $this->_getStoreConfig(self::XML_PATH_ENABLED);
    }

    protected function _getClientId()
    {
        return $this->_getStoreConfig(self::XML_PATH_CLIENT_ID);
    }

    protected function _getClientSecret()
    {
        return $this->_getStoreConfig(self::XML_PATH_CLIENT_SECRET);
    }

    protected function _getStoreConfig($xmlPath)
    {
        return Mage::getStoreConfig($xmlPath, Mage::app()->getStore()->getId());
    }

}