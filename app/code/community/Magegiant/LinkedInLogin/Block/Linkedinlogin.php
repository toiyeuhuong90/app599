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
 * @package     Magegiant_LinkedInLogin
 * @copyright   Copyright (c) 2014 Magegiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * Linkedinlogin Block
 * 
 * @category    Magegiant
 * @package     Magegiant_LinkedInLogin
 * @author      Magegiant Developer
 */
class Magegiant_LinkedInLogin_Block_Linkedinlogin extends Magegiant_SocialLogin_Block_Abstract implements Magegiant_SocialLogin_Block_Interface
{
	public function __construct()
	{

		parent::__construct();
		$this->setTemplate('magegiant/sociallogin/linkedinlogin/linkedinlogin.phtml');
	}

	/**
	 * @return string
	 */
	public function getButtonImage()
	{
		$baseUrl   = Mage::helper('sociallogin')->getSocialImgUrl();
		$imgConfig = Mage::helper('linkedinlogin')->getLinkedInConfig('li_image') ? Mage::helper('linkedinlogin')->getLinkedInConfig('li_image') : 'default/linkedin.jpg';

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
		return $this->getUrl('linkedinlogin/index/login');
	}

	/**
	 * get label for button
	 *
	 * @return mixed
	 */
	public function getButtonLabel()
	{
		// TODO: Implement getButtonLabel() method.
		return Mage::helper('linkedinlogin')->getLinkedInConfig('linkedin_image_label');
	}
}