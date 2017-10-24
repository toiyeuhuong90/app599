<?php


class QSoft_Customer_Block_Adminhtml_Schedulebodyscan extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {

        $this->_controller = "adminhtml_schedulebodyscan";
        $this->_blockGroup = "qsoft_customer";
        $this->_headerText = Mage::helper("customer")->__("Schedule body scan");

        parent::__construct();
        $this->removeButton('add');
    }

}