<?php

class QSoft_SocialConnect_Block_Register extends Mage_Core_Block_Template
{
    protected $clientGoogle = null;
    protected $clientFacebook = null;
    protected $clientTwitter = null;
    protected $clientLinkedin = null;
    protected $clientVk = null;

    protected $numEnabled = 0;
    protected $numShown = 0;

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

        Mage::register('qsoft_socialconnect_button_text', $this->__('Register'), true);

        $this->setTemplate('qsoft/socialconnect/register.phtml');
    }

    protected function _getColSet()
    {
        return 'col' . $this->numEnabled . '-set';
    }

    protected function _getCol()
    {
        return 'col-' . ++$this->numShown;
    }

    protected function _googleEnabled()
    {
        return (bool)$this->clientGoogle->isEnabled();
    }

    protected function _facebookEnabled()
    {
        return (bool)$this->clientFacebook->isEnabled();
    }

    protected function _twitterEnabled()
    {
        return (bool)$this->clientTwitter->isEnabled();
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
