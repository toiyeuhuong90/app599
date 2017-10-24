<?php

class QSoft_SocialConnect_Block_Twitter_Account extends Mage_Core_Block_Template
{
    /**
     *
     * @var QSoft_SocialConnect_Model_Twitter_Oauth_Client
     */
    protected $client = null;

    /**
     *
     * @var QSoft_SocialConnect_Model_Twitter_Info_User
     */
    protected $userInfo = null;

    protected function _construct() {
        parent::_construct();

        $this->client = Mage::getSingleton('qsoft_socialconnect/twitter_oauth_client');
        if(!($this->client->isEnabled())) {
            return;
        }

        $this->userInfo = Mage::registry('qsoft_socialconnect_twitter_userinfo');

        $this->setTemplate('qsoft/socialconnect/twitter/account.phtml');

    }

    protected function _hasData()
    {
        return $this->userInfo->hasData();
    }


    protected function _getTwitterId()
    {
        return $this->userInfo->getId();
    }

    protected function _getStatus()
    {
        return '<a href="'.sprintf('https://twitter.com/%s', $this->userInfo->getScreenName()).'" target="_blank">'.
                    $this->escapeHtml($this->userInfo->getScreenName()).'</a>';
    }

    protected function _getPicture()
    {
        if($this->userInfo->getProfileImageUrl()) {
            return Mage::helper('qsoft_socialconnect/twitter')
                    ->getProperDimensionsPictureUrl($this->userInfo->getId(),
                            $this->userInfo->getProfileImageUrl());
        }

        return null;
    }

    protected function _getName()
    {
        return $this->userInfo->getName();
    }

}
