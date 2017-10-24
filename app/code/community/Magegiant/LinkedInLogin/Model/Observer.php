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
 * LinkedInLogin Observer Model
 * 
 * @category    MageGiant
 * @package     MageGiant_LinkedInLogin
 * @author      MageGiant Developer
 */
class Magegiant_LinkedInLogin_Model_Observer
{
    /**
     * process controller_action_predispatch event
     *
     * @return Magegiant_LinkedInLogin_Model_Observer
     */
    public function addLinkedInButton($observer)
	{
		if (!Mage::helper('linkedinlogin')->isEnabled())
			return $this;
		$current = $observer->getCurrent();
		$button  = Mage::helper('linkedinlogin')->getLinkedInButton();
		$enable  = Mage::helper('linkedinlogin')->isEnabled();
		$sort    = Mage::helper('linkedinlogin')->getSort();
		$current->addSocialButton($button, $enable, 'bt-loginlinkedin', $sort);

		return $this;
	}
}