<?php
/**
 * Magegiant
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the magegiant.com license that is
 * available through the world-wide-web at this URL:
 * http://magegiant.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magegiant
 * @package     Magegiant_GgLogin
 * @copyright   Copyright (c) 2014 Magegiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * GgLogin Index Controller
 *
 * @category    Magegiant
 * @package     Magegiant_InstagramLogin
 * @author      Magegiant Developer
 */
class Magegiant_InstagramLogin_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * process login action
     *
     * @return bool|mixed|void
     */
    public function loginAction()
    {
        $instagram = Mage::getModel('instagramlogin/instagramlogin')->getInstagram();
        $loginUrl  = $instagram->getLoginUrl();
        exit(header('Location: ' . $loginUrl));
    }

    /**
     * process user action
     */
    public function callbackAction()
    {
        $instagram = Mage::getModel('instagramlogin/instagramlogin')->getInstagram();
        $code      = $this->getRequest()->getParam('code');

        if (!$code) {
            Mage::getSingleton('core/session')->addError('Login failed as you have not granted access.');
            $this->_appendJs("<script type=\"text/javascript\">try{window.opener.location.reload(true);}catch(e){window.opener.location.href=\"" . Mage::getBaseUrl() . "\"} window.close();</script>");
        }
        $data    = $instagram->getOAuthToken($code);
        $user    = array();
        $name    = $data->user->full_name;
        $arrName = explode(' ', $name, 2);
        $email   = $name . '@instagram.com';
        if (empty($arrName[0])) {
            $arrName[0] = $name;
        }
        if (!empty($arrName[1])) {
            $user['lastname']  = $arrName[1];
        } else {
            $user['lastname']  = 'yourLastName';
        }
        $user['firstname'] = $arrName[0];
        $user['email']     = $email;


        //get website_id and sote_id of each stores
        $store_id   = Mage::app()->getStore()->getStoreId(); //add
        $website_id = Mage::app()->getStore()->getWebsiteId(); //add

        $customer = Mage::helper('sociallogin')->getCustomerByEmail($user['email'], $website_id); //add edition
        if(!$customer || $customer->getId()){
            //Login multisite
            $customer = Mage::helper('sociallogin')->createCustomerMultiWebsite($user, $website_id, $store_id);
        }

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

        $nextUrl = Mage::helper('sociallogin')->getEditUrl();
        $this->_appendJs("<script>window.close();window.opener.location.href = '$nextUrl';</script>");
        Mage::getSingleton('core/session')->addNotice($this->__('Update your contact details.'));
    }

    protected function _appendJs($string)
    {
        $this->loadLayout();
        $layout = Mage::app()->getLayout();
        $block  = $layout->createBlock('core/text');
        $block->setText(
            $string
        );
        $this->getLayout()->getBlock('head')->append($block);
        $this->renderLayout();
    }
}