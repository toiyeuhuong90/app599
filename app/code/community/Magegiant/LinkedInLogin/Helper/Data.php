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
 * @package     MageGiant_LinkedInLogin
 * @copyright   Copyright (c) 2014 MageGiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * LinkedInLogin Helper
 *
 * @category    MageGiant
 * @package     MageGiant_LinkedInLogin
 * @author      MageGiant Developer
 */
class Magegiant_LinkedInLogin_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_ENABLED = 'linkedinlogin/general/is_enabled';
	const XML_PATH_LINKEDIN_CONFIG = 'sociallogin/linkedin/';

	public function isEnabled($storeId = null)
	{
		if (!$storeId) {
			$storeId = Mage::app()->getStore()->getId();
		}

		return Mage::getStoreConfig(self::XML_PATH_ENABLED, $storeId);
	}

	public function getLinkedInConfig($name, $storeId = null)
	{
		if (!$storeId) {
			$storeId = Mage::app()->getStore()->getId();
		}

		return Mage::getStoreConfig(self::XML_PATH_LINKEDIN_CONFIG . $name, $storeId);

	}

	public function getLinkedInButton()
	{
		return Mage::getBlockSingleton('linkedinlogin/linkedinlogin');
	}

	public function getSort()
	{
		return $this->getLinkedInConfig('position');
	}

	public function generateRandomString($length = 3) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}