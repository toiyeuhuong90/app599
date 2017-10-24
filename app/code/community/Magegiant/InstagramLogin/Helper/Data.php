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
 * GgLogin Helper
 *
 * @category    MageGiant
 * @package     MageGiant_InstagramLogin
 * @author      MageGiant Developer
 */
class Magegiant_InstagramLogin_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_PATH_INSTAGRAM        = 'sociallogin/instagram/';
    const XML_PATH_INSTAGRAM_ENABLE = 'sociallogin/instagram/enable';

    /**
     * get facebook api config
     *
     * @param type $code
     * @param type $store
     * @return type
     */
    function getInstagramConfig($code, $store = null)
    {
        if (!$store)
            $store = Mage::app()->getStore()->getId();

        return Mage::getStoreConfig(self::XML_PATH_INSTAGRAM . $code, $store);
    }

    /**
     * check facebook login is enabled
     *
     * @param mixed $store
     * @return boolean
     */
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_INSTAGRAM_ENABLE, $store);
    }

    /**
     * get button sort order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getInstagramConfig('sort_order');
    }

    /**
     * get button
     *
     * @return image html
     */
    public function getInstagramButton()
    {
        return Mage::getBlockSingleton('instagramlogin/instagramlogin');
    }

}