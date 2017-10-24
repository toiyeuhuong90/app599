<?php

class QSoft_SocialConnect_Block_Linkedin_Account extends Mage_Core_Block_Template
{
    /**
     *
     * @var QSoft_SocialConnect_Model_Linkedin_Oauth2_Client
     */
    protected $client = null;
    
    /**
     *
     * @var QSoft_SocialConnect_Model_Linkedin_Info_User
     */
    protected $userInfo = null;

    protected function _construct() {
        parent::_construct();

        $this->client = Mage::getSingleton('qsoft_socialconnect/linkedin_oauth2_client');
        if(!($this->client->isEnabled())) {
            return;
        }

        $this->userInfo = Mage::registry('qsoft_socialconnect_linkedin_userinfo');

        $this->setTemplate('qsoft/socialconnect/linkedin/account.phtml');
    }

    protected function _hasData()
    {
        return $this->userInfo->hasData();
    }

    protected function _getLinkedinId()
    {
        return $this->userInfo->getId();
    }

    protected function _getStatus()
    {
        $siteStandardProfileRequest = $this->userInfo->getSiteStandardProfileRequest();
        if($siteStandardProfileRequest && !empty($siteStandardProfileRequest->url)) {
            $link = '<a href="'.$siteStandardProfileRequest->url.'" target="_blank">'.
                    $this->escapeHtml($this->_getName()).'</a>';
        } else {
            $link = $this->_getName();
        }

        return $link;
    }

    protected function _getPublicProfileUrl()
    {
        if($this->userInfo->getPublicProfileUrl()) {
            $link = '<a href="'.$this->userInfo->getPublicProfileUrl().'" target="_blank">'.
                    $this->escapeHtml($this->userInfo->getPublicProfileUrl()).'</a>';

            return $link;
        }

        return null;
    }

    protected function _getEmail()
    {
        return $this->userInfo->getEmailAddress();
    }

    protected function _getPicture()
    {
        if($this->userInfo->getPictureUrl()) {
            return Mage::helper('qsoft_socialconnect/linkedin')
                    ->getProperDimensionsPictureUrl($this->userInfo->getId(),
                            $this->userInfo->getPictureUrl());
        }

        return null;
    }

    protected function _getName()
    {
        return sprintf(
            '%s %s',
            $this->userInfo->getFirstName(),
            $this->userInfo->getLastName()
        );
    }

}