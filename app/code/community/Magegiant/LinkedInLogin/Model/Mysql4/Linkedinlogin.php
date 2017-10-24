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
 * Linkedinlogin Resource Model
 * 
 * @category    MageGiant
 * @package     MageGiant_LinkedInLogin
 * @author      MageGiant Developer
 */
class Magegiant_LinkedInLogin_Model_Mysql4_Linkedinlogin extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('linkedinlogin/linkedinlogin', 'linkedinlogin_id');
    }
}