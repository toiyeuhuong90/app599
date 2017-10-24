<?php

class QSoft_ProductDesign_Block_Adminhtml_Frontendgroup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId    = 'id';
        $this->_blockGroup  = 'productdesign';
        $this->_controller  = 'adminhtml_frontendgroup';

        $this->_updateButton('save', 'label', Mage::helper("productdesign")->__('Save Group'));
        $this->_updateButton('delete', 'label', Mage::helper("productdesign")->__('Delete Group'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        return Mage::helper("productdesign")->__("Edit Group '%s'", $this->htmlEscape(Mage::registry('group_option_data')->getTitle()));
    }
}