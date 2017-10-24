<?php


class QSoft_ProductDesign_Block_Adminhtml_Group extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {

        $this->_controller = "adminhtml_group";
        $this->_blockGroup = "productoptions";
        $this->_headerText = Mage::helper("productdesign")->__("Group Manager");
        $this->_addButtonLabel = Mage::helper("productdesign")->__("Add New Item");
        parent::__construct();

    }

}