<?php

class QSoft_ProductDesign_Block_Adminhtml_Frontendgroup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('productoptions_group_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper("productdesign")->__('Group Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'     => Mage::helper("productdesign")->__('Group Info'),
            'title'     => Mage::helper("productdesign")->__('Group Info'),
            'content'   => $this->getLayout()->createBlock('productdesign/adminhtml_frontendgroup_edit_tab_main')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}