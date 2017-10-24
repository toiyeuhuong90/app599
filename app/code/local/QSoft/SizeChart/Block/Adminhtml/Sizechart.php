<?php


class QSoft_SizeChart_Block_Adminhtml_Sizechart extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_sizechart";
	$this->_blockGroup = "sizechart";
	$this->_headerText = Mage::helper("sizechart")->__("Sizechart Manager");
	$this->_addButtonLabel = Mage::helper("sizechart")->__("Add New Item");
	parent::__construct();
	
	}

}