<?php

class QSoft_SocialConnect_Model_Twitter_Info_User extends QSoft_SocialConnect_Model_Twitter_Info
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
     * @return QSoft_SocialConnect_Model_Twitter_Info_User
     */
    public function load($id = null)
    {
        if(is_null($id) && Mage::getSingleton('customer/session')->isLoggedIn()) {
            $this->customer = Mage::getSingleton('customer/session')->getCustomer();
        } else if(is_int($id)) {
            $this->customer = Mage::getModel('customer/customer')->load($id);
            
            // TODO: Implement
        }

        if(!$this->customer->getId()) {
            return $this;
        }

        if(!($socialconnectLid = $this->customer->getQSoftSocialconnectTid()) ||
                !($socialconnectTtoken = $this->customer->getQSoftSocialconnectTtoken())) {
            return $this;
        }

        $this->setAccessToken($socialconnectTtoken);
        $this->_load();

        return $this;
    }

    protected function _onException($e) {
        parent::_onException($e);

        $helper = Mage::helper('qsoft_socialconnect/twitter');
        /* @var $helper QSoft_SocialConnect_Helper_Twitter */

        $helper->disconnect($this->customer);
    }

}