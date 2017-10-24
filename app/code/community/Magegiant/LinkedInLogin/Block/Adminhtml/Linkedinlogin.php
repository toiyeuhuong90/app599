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
 * Linkedinlogin Adminhtml Block
 * 
 * @category    Magegiant
 * @package     Magegiant_LinkedInLogin
 * @author      Magegiant Developer
 */
class Magegiant_LinkedInLogin_Block_Adminhtml_Linkedinlogin extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_linkedinlogin';
        $this->_blockGroup = 'linkedinlogin';
        $this->_headerText = Mage::helper('linkedinlogin')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('linkedinlogin')->__('Add Item');
        parent::__construct();
    }
}