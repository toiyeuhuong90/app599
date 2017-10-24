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
 * Linkedinlogin Edit Form Content Tab Block
 * 
 * @category    Magegiant
 * @package     Magegiant_LinkedInLogin
 * @author      Magegiant Developer
 */
class Magegiant_LinkedInLogin_Block_Adminhtml_Linkedinlogin_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magegiant_LinkedInLogin_Block_Adminhtml_Linkedinlogin_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getLinkedInLoginData()) {
            $data = Mage::getSingleton('adminhtml/session')->getLinkedInLoginData();
            Mage::getSingleton('adminhtml/session')->setLinkedInLoginData(null);
        } elseif (Mage::registry('linkedinlogin_data')) {
            $data = Mage::registry('linkedinlogin_data')->getData();
        }
        $fieldset = $form->addFieldset('linkedinlogin_form', array(
            'legend'=>Mage::helper('linkedinlogin')->__('Item information')
        ));

        $fieldset->addField('title', 'text', array(
            'label'        => Mage::helper('linkedinlogin')->__('Title'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'title',
        ));

        $fieldset->addField('filename', 'file', array(
            'label'        => Mage::helper('linkedinlogin')->__('File'),
            'required'    => false,
            'name'        => 'filename',
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('linkedinlogin')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('linkedinlogin/status')->getOptionHash(),
        ));

        $fieldset->addField('content', 'editor', array(
            'name'        => 'content',
            'label'        => Mage::helper('linkedinlogin')->__('Content'),
            'title'        => Mage::helper('linkedinlogin')->__('Content'),
            'style'        => 'width:700px; height:500px;',
            'wysiwyg'    => false,
            'required'    => true,
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}