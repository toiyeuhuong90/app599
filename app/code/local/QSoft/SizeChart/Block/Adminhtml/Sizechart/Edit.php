<?php
	
class QSoft_SizeChart_Block_Adminhtml_Sizechart_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "sizechart";
				$this->_controller = "adminhtml_sizechart";
				$this->_updateButton("save", "label", Mage::helper("sizechart")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("sizechart")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("sizechart")->__("Save And Continue Edit"),
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
				if( Mage::registry("sizechart_data") && Mage::registry("sizechart_data")->getId() ){

				    return Mage::helper("sizechart")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("sizechart_data")->getId()));

				} 
				else{

				     return Mage::helper("sizechart")->__("Add Item");

				}
		}
}