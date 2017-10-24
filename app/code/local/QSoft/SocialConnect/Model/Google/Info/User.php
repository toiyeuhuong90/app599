<?php

class QSoft_SocialConnect_Model_Google_Info_User extends QSoft_SocialConnect_Model_Google_Info
{
    
    /**
     *
     * @var type Mage_Core_Model_Customer
     */
    protected $customer = null;


    /**
     * Load customer user info
     *
     * @param null|int $id Customer Id
     * @return QSoft_SocialConnect_Model_Google_Userinfo
     */
    public function load($id = null)
    {
        if(is_null($id) && Mage::getSingleton('customer/session')->isLoggedIn()) {
            $this->customer = Mage::getSingleton('customer/session')->getCustomer();
        } else if(is_int($id)){
            $this->customer = Mage::getModel('customer/customer')->load($id);
            
            // TODO: Implement
        }

        if(!$this->customer->getId()) {
            return $this;
        }

        if(!($socialconnectGid = $this->customer->getQSoftSocialconnectGid()) ||
                !($socialconnectGtoken = $this->customer->getQSoftSocialconnectGtoken())) {
            return $this;
        }

        $this->setAccessToken($socialconnectGtoken);
        $this->_load();

        return $this;
    }

    protected function _onException($e) {
        parent::_onException($e);

        $helper = Mage::helper('qsoft_socialconnect/google');
        /* @var $helper QSoft_SocialConnect_Helper_Google */

        $helper->disconnect($this->customer);
    }

}