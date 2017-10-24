<?php

class QSoft_SocialConnect_Model_Google_Info extends Varien_Object
{
    protected $params = array();

    /**
     * Google client model
     *
     * @var QSoft_SocialConnect_Model_Google_Oauth2_Client
     */
    protected $client = null;

    public function _construct()
    {
        parent::_construct();

        $this->client = Mage::getSingleton('qsoft_socialconnect/google_oauth2_client');
        if (!($this->client->isEnabled())) {
            return $this;
        }
    }

    /**
     * Get Google client model
     *
     * @return QSoft_SocialConnect_Model_Google_Oauth2_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    public function setClient(QSoft_SocialConnect_Model_Google_Oauth2_Client $client)
    {
        $this->client = $client;
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    /**
     * Get Google client's access token
     *
     * @return stdClass
     */
    public function getAccessToken()
    {
        return $this->client->getAccessToken();
    }

    public function load($id = null)
    {
        $this->_load();

        return $this;
    }

    protected function _load()
    {
        try {
            $response = $this->client->api(
                '/userinfo',
                'GET',
                $this->params
            );

            foreach ($response as $key => $value) {
                $this->{$key} = $value;
            }

        } catch (QSoft_SocialConnect_Model_Google_OAuth2_Exception $e) {
            $this->_onException($e);
        } catch (Exception $e) {
            $this->_onException($e);
        }
    }

    protected function _onException($e)
    {
        if ($e instanceof QSoft_SocialConnect_Model_Google_OAuth2_Exception) {
            Mage::getSingleton('core/session')->addNotice($e->getMessage());
        } else {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
    }

}