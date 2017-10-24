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
 * Linkedinlogin Edit Tabs Block
 * 
 * @category    Magegiant
 * @package     Magegiant_LinkedInLogin
 * @author      Magegiant Developer
 */
class Magegiant_LinkedInLogin_Block_Adminhtml_Linkedinlogin_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('linkedinlogin_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('linkedinlogin')->__('Item Information'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Magegiant_LinkedInLogin_Block_Adminhtml_Linkedinlogin_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('linkedinlogin')->__('Item Information'),
            'title'     => Mage::helper('linkedinlogin')->__('Item Information'),
            'content'   => $this->getLayout()
                                ->createBlock('linkedinlogin/adminhtml_linkedinlogin_edit_tab_form')
                                ->toHtml(),
        ));
        $this->_updateActiveTab();
        return parent::_beforeToHtml();
    }
    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if ($tabId) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if ($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }
}