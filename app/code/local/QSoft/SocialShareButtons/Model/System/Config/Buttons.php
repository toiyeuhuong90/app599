<?php

class QSoft_SocialShareButtons_Model_System_Config_Buttons
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'fb', 'label'=>Mage::helper('adminhtml')->__('Facebook')),
            array('value' => 'tw', 'label'=>Mage::helper('adminhtml')->__('Twitter')),
            array('value' => 'li', 'label'=>Mage::helper('adminhtml')->__('Linkedin')),
            array('value' => 'yt', 'label'=>Mage::helper('adminhtml')->__('Youtube')),
            array('value' => 'gp', 'label'=>Mage::helper('adminhtml')->__('Google +')),
            array('value' => 'pi', 'label'=>Mage::helper('adminhtml')->__('Pinterest')),
            array('value' => 'mail', 'label'=>Mage::helper('adminhtml')->__('Email')),
            array('value' => 're', 'label'=>Mage::helper('adminhtml')->__('Reddit')),
            array('value' => 'st', 'label'=>Mage::helper('adminhtml')->__('Stumbleupon')),
        );
    }
}
