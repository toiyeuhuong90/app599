<?php

class QSoft_Customer_Block_Adminhtml_Schedulebodyscan_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("qsoft_customer_form", array("legend" => Mage::helper("qsoft_customer")->__("Item information")));


        $fieldset->addField("firstname", "text", array(
            "label" => Mage::helper("qsoft_customer")->__("FirstName"),
            "class" => "required-entry",
            "required" => true,
            "name" => "firstname",
        ));

        $fieldset->addField("lastname", "text", array(
            "label" => Mage::helper("qsoft_customer")->__("LastName"),
            "class" => "required-entry",
            "required" => true,
            "name" => "lastname",
        ));

        $fieldset->addField("email", "text", array(
            "label" => Mage::helper("qsoft_customer")->__("Email"),
            "class" => "required-entry validate-email",
            "required" => true,
            "name" => "email",
        ));

        $fieldset->addField("book_time", "text", array(
            "label" => Mage::helper("qsoft_customer")->__("LastName"),
            "class" => "required-entry",
            "readonly" => true,
            "name" => "book_time",
        ));

        $fieldset->addField("telephone", "text", array(
            "label" => Mage::helper("qsoft_customer")->__("Telephone"),
            "name" => "telephone",
        ));

        $fieldset->addField("address", "textarea", array(
            "label" => Mage::helper("qsoft_customer")->__("Address"),
            "name" => "address",
        ));

        $fieldset->addField("note", "textarea", array(
            "label" => Mage::helper("qsoft_customer")->__("Message"),
            "name" => "note",
        ));

        $fieldset->addType("interested", "QSoft_Customer_Block_Adminhtml_Schedulebodyscan_Edit_Renderer_Interested");
        $fieldset->addField("interested", "interested", array(
            "label" => Mage::helper("qsoft_customer")->__("Interested in"),
            "name" => "interested",
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('qsoft_customer')->__('Status'),
            'values' => QSoft_Customer_Block_Adminhtml_Schedulebodyscan_Grid::getValueArray5(),
            'name' => 'status',
        ));

        if (Mage::getSingleton("adminhtml/session")->getGroupData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getGroupData());
            Mage::getSingleton("adminhtml/session")->setGroupData(null);
        } elseif (Mage::registry("group_data")) {
            $form->setValues(Mage::registry("group_data")->getData());
        }
        return parent::_prepareForm();
    }
}
