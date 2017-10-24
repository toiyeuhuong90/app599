<?php

class QSoft_SocialConnect_Block_Login extends Mage_Core_Block_Template
{
    protected $clientGoogle = null;
    protected $clientFacebook = null;
    protected $clientTwitter = null;
    protected $clientLinkedin = null;
    protected $clientVk = null;

    protected $numEnabled = 0;
    protected $numDescShown = 0;
    protected $numButtShown = 0;

    protected function _construct()
    {
        parent::_construct();

        $this->clientGoogle = Mage::getSingleton('qsoft_socialconnect/google_oauth2_client');
        $this->clientFacebook = Mage::getSingleton('qsoft_socialconnect/facebook_oauth2_client');
        $this->clientTwitter = Mage::getSingleton('qsoft_socialconnect/twitter_oauth_client');
        $this->clientLinkedin = Mage::getSingleton('qsoft_socialconnect/linkedin_oauth2_client');
        $this->clientVk = Mage::getSingleton('qsoft_socialconnect/vk_oauth2_client');

        if (!$this->_googleEnabled() &&
            !$this->_facebookEnabled() &&
            !$this->_twitterEnabled() &&
            !$this->_linkedinEnabled() &&
            !$this->_vkEnabled()
        ) {
            return;
        }

        if ($this->_googleEnabled()) {
            $this->numEnabled++;
        }

        if ($this->_facebookEnabled()) {
            $this->numEnabled++;
        }

        if ($this->_twitterEnabled()) {
            $this->numEnabled++;
        }

        if ($this->_linkedinEnabled()) {
            $this->numEnabled++;
        }

        if ($this->_vkEnabled()) {
            $this->numEnabled++;
        }

        Mage::register('qsoft_socialconnect_button_text', $this->__('Login'), true);

        $this->setTemplate('qsoft/socialconnect/login.phtml');
    }

    protected function _getColSet()
    {
        return 'col' . $this->numEnabled . '-set';
    }

    protected function _getDescCol()
    {
        return 'col-' . ++$this->numDescShown;
    }

    protected function _getButtCol()
    {
        return 'col-' . ++$this->numButtShown;
    }

    protected function _googleEnabled()
    {
        return $this->clientGoogle->isEnabled();
    }

    protected function _facebookEnabled()
    {
        return $this->clientFacebook->isEnabled();
    }

    protected function _twitterEnabled()
    {
        return $this->clientTwitter->isEnabled();
    }

    protected function _linkedinEnabled()
    {
        return $this->clientLinkedin->isEnabled();
    }

    protected function  _vkEnabled()
    {
        return $this->clientVk->isEnabled();
    }

}
