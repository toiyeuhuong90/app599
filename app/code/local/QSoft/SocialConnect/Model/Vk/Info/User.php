<?php
/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 11/16/2015
 * Time: 9:40 AM
 */

class QSoft_SocialConnect_Model_Vk_Info_User extends QSoft_SocialConnect_Model_Vk_Info
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
     * @return QSoft_SocialConnect_Model_Vk_Info_User
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

        if(!($socialconnectVkid = $this->customer->getQSoftSocialconnectVkid()) ||
            !($socialconnectVktoken = $this->customer->getQSoftSocialconnectVktoken())) {
            return $this;
        }

        $this->setAccessToken(unserialize($socialconnectVktoken));
        $this->_load();

        return $this;
    }

    /**
     * On Exception
     *
     * @param $e
     */
    protected function _onException($e) {
        parent::_onException($e);

        $helper = Mage::helper('qsoft_socialconnect/vk');
        /* @var $helper QSoft_SocialConnect_Helper_Vk */

        $helper->disconnect($this->customer);
    }

}