<?php

class QSoft_Custom_Block_Adminhtml_Location extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = "adminhtml_location";
        $this->_blockGroup = "qsoft_custom";
        $this->_headerText = Mage::helper("qsoft_custom")->__("Location Management");
        parent::__construct();
        $this->removeButton('add');
    }
}