<?php
class QSoft_Customer_Block_Adminhtml_Schedulebodyscan_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("group_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("qsoft_customer")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("qsoft_customer")->__("Item Information"),
				"title" => Mage::helper("qsoft_customer")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("qsoft_customer/adminhtml_schedulebodyscan_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
