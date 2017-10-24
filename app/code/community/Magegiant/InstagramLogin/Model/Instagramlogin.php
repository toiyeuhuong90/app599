<?php
/**
 * MageGiant
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageGiant.com license that is
 * available through the world-wide-web at this URL:
 * http://magegiant.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    MageGiant
 * @package     MageGiant_GgLogin
 * @copyright   Copyright (c) 2014 MageGiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * Gglogin Model
 *
 * @category    MageGiant
 * @package     MageGiant_InstagramLogin
 * @author      MageGiant Developer
 */
require_once Mage::getBaseDir('base') . DS . 'lib' . DS . 'Magegiant' . DS . 'Instagram' . DS . 'Instagram.php';

class Magegiant_InstagramLogin_Model_Instagramlogin extends Instagram
{
    protected $_config;
    protected $_instagram;

    public function __construct()
    {

        $ClientID      = Mage::helper('instagramlogin')->getInstagramConfig('client_id');
        $ClientSecret  = Mage::helper('instagramlogin')->getInstagramConfig('client_secret');
        $RedirectUri   = Mage::getUrl('instagramlogin/index/callback');
        $this->_config = array(
            'apiKey'      => $ClientID,
            'apiSecret'   => $ClientSecret,
            'apiCallback' => $RedirectUri,
        );
        $this->_instagram = new Instagram($this->_config);
    }

    public function getConfig()
    {
        return $this->_config;
    }
    public function  getInstagram(){
        return $this->_instagram;
    }
}