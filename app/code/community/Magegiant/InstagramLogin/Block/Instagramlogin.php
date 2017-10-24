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
 * @package     Magegiant_InstagramLogin
 * @copyright   Copyright (c) 2014 Magegiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * Instagramlogin Block
 * 
 * @category    Magegiant
 * @package     Magegiant_InstagramLogin
 * @author      Magegiant Developer
 */
class Magegiant_InstagramLogin_Block_Instagramlogin extends Magegiant_SocialLogin_Block_Abstract implements Magegiant_SocialLogin_Block_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('magegiant/sociallogin/instagramlogin/instagramlogin.phtml');
    }

    /**
     * @return string
     */
    public function getButtonImage()
    {
        $baseUrl   = Mage::helper('sociallogin')->getSocialImgUrl();
        $imgConfig = Mage::helper('instagramlogin')->getInstagramConfig('instagram_image') ? Mage::helper('instagramlogin')->getInstagramConfig('instagram_image') : 'default/instagram.png';

        return $baseUrl . $imgConfig;
    }

    /**
     * get social url
     *
     * @return return social url
     */
    public function getLoginUrl()
    {
        // TODO: Implement getLoginUrl() method.
        return $this->getUrl('instagramlogin/index/login');
    }

    /**
     * get label for button
     *
     * @return mixed
     */
    public function getButtonLabel()
    {
        // TODO: Implement getButtonLabel() method.
        return Mage::helper('instagramlogin')->getInstagramConfig('instagram_image_label');
    }
}