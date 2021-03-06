<?php
	
class QSoft_Customer_Block_Adminhtml_Schedulebodyscan_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "qsoft_customer";
				$this->_controller = "adminhtml_schedulebodyscan";
				$this->_updateButton("save", "label", Mage::helper("qsoft_customer")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("qsoft_customer")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("qsoft_customer")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("group_data") && Mage::registry("group_data")->getId() ){

				    return Mage::helper("qsoft_customer")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("group_data")->getId()));

				} 
				else{

				     return Mage::helper("qsoft_customer")->__("Add Item");

				}
		}
}