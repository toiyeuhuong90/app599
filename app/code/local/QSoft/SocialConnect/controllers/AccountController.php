<?php

class QSoft_SocialConnect_AccountController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return $this;
        }

        /*
         * Avoid situations where before_auth_url redirects when doing connect
         * and disconnect from account dashboard. Authenticate.
         */
        if (!Mage::getSingleton('customer/session')
                ->unsBeforeAuthUrl()
                ->unsAfterAuthUrl()
                ->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }

    }

    public function googleAction()
    {
        $userInfo = Mage::getSingleton('qsoft_socialconnect/google_info_user')
                ->load();

        Mage::register('qsoft_socialconnect_google_userinfo', $userInfo);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function facebookAction()
    {
        $userInfo = Mage::getSingleton('qsoft_socialconnect/facebook_info_user')
            ->load();

        Mage::register('qsoft_socialconnect_facebook_userinfo', $userInfo);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function twitterAction()
    {
        // Cache user info inside customer session due to Twitter window frame rate limits
        if(!($userInfo = Mage::getSingleton('customer/session')
                ->getQSoftSocialconnectTwitterUserinfo()) || !$userInfo->hasData()) {
            
            $userInfo = Mage::getSingleton('qsoft_socialconnect/twitter_info_user')
                ->load();

            Mage::getSingleton('customer/session')
                ->getQSoftSocialconnectTwitterUserinfo($userInfo);
        }

        Mage::register('qsoft_socialconnect_twitter_userinfo', $userInfo);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function linkedinAction()
    {
        $userInfo = Mage::getSingleton('qsoft_socialconnect/linkedin_info_user')
            ->load();

        Mage::register('qsoft_socialconnect_linkedin_userinfo', $userInfo);

        $this->loadLayout();
        $this->renderLayout();
    }

}
