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
 * @package     Magegiant_SocialLogin
 * @copyright   Copyright (c) 2014 Magegiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * Sociallogin Block
 *
 * @category    Magegiant
 * @package     Magegiant_SocialLogin
 * @author      Magegiant Developer
 */
class Magegiant_InstagramLogin_Block_Adminhtml_System_Config_Form_Field_RedirectUrl
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html_id     = $element->getHtmlId();
        $redirectUrl = $this->_redirectUrl();
        $redirectUrl = str_replace('index.php/', '', $redirectUrl);
        $html        = '<input readonly id="' . $html_id . '" class="input-text" value="' . $redirectUrl . '" onclick="this.select()">';
        return $html;
    }
    protected function _redirectUrl()
    {
        return Mage::getUrl('instagramlogin/index/callback',array('_secure'=>true));

    }
}
