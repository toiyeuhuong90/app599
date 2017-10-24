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
 * GgLogin Observer Model
 *
 * @category    MageGiant
 * @package     MageGiant_InstagramLogin
 * @author      MageGiant Developer
 */
class Magegiant_InstagramLogin_Model_Frontend_Observer
{
    /**
     * process controller_action_predispatch event
     *
     * @return Magegiant_GgLogin_Model_Observer
     */
    public function addInstagramButton($observer)
    {
        if (!Mage::helper('instagramlogin')->isEnabled())
            return $this;
        $current = $observer->getCurrent();
        $button  = Mage::helper('instagramlogin')->getInstagramButton();
        $enable  = Mage::helper('instagramlogin')->isEnabled();
        $sort    = Mage::helper('instagramlogin')->getSortOrder();
        $current->addSocialButton($button, $enable, 'bt-login', $sort);
        return $this;
    }
}