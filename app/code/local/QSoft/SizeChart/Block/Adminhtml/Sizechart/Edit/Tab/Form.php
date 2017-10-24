<?php

class QSoft_SizeChart_Block_Adminhtml_Sizechart_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("sizechart_form", array("legend" => Mage::helper("sizechart")->__("Item information")));


        $fieldset->addField("name", "text", array(
            "label" => Mage::helper("sizechart")->__("Name"),
            "class" => "required-entry",
            "required" => true,
            "name" => "name",
        ));

        $icon = $fieldset->addField("main_image", "text", array(
            "label" => Mage::helper("sizechart")->__("Main Image"),
            "class" => "required-entry",
            "required" => true,
            "name" => "main_image",
        ));
        $form->getElement('main_image')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $icon
            ))
        );

        $icon = $fieldset->addField("image_cm", "text", array(
            "label" => Mage::helper("sizechart")->__("Image for Cm"),
            "class" => "required-entry",
            "required" => true,
            "name" => "image_cm",
        ));

        $form->getElement('image_cm')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $icon
            ))
        );

        $icon = $fieldset->addField("image_inch", "text", array(
            "label" => Mage::helper("sizechart")->__("Image for Inch"),
            "class" => "required-entry",
            "required" => true,
            "name" => "image_inch",
        ));

        $form->getElement('image_inch')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $icon
            ))
        );

        if (Mage::getSingleton("adminhtml/session")->getSizechartData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getSizechartData());
            Mage::getSingleton("adminhtml/session")->setSizechartData(null);
        } elseif (Mage::registry("sizechart_data")) {
            $form->setValues(Mage::registry("sizechart_data")->getData());
        }
        return parent::_prepareForm();
    }
}
