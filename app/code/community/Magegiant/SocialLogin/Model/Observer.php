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
 * @package     MageGiant_ThankYouPage
 * @copyright   Copyright (c) 2014 MageGiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * SocialLogin Observer Model
 *
 * @category    MageGiant
 * @package     MageGiant_SocialLogin
 * @author      MageGiant Developer
 */
class Magegiant_SocialLogin_Model_Observer
{
	public function checkRequired()
	{
		$cache = Mage::app()->getCache();
		if (!$this->_canShow()) return $this;

		if ($cache->load('magegiant_community') === false) {
			$message = 'Important: Please setup Magegiant_Community in order to finish installation.<br />
						Please download <a href="http://go.magegiant.com/download-magegiant-community" target="_blank">Magegiant_Community</a> and setup it via Magento Connect.';
			Mage::getSingleton('adminhtml/session')->addNotice($message);
			$cache->save('true', 'magegiant_community', array('magegiant_community'), $lifeTime = 10);
		}

	}

	protected function _canShow()
	{
		return Mage::getSingleton('admin/session')->isLoggedIn() AND
		!Mage::getConfig()->getModuleConfig('Magegiant_Community')->is('active', 'true');

	}
}