<?php

class QSoft_ProductDesign_Block_Adminhtml_Catalog_Product_Edit_Tab_Group extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("design_qsoft_form", array("legend" => Mage::helper("productdesign")->__("Design information")));

        $banner = $fieldset->addField("bg_front", "text", array(
            "label" => Mage::helper("productdesign")->__("Image background Front"),
            "name" => "bg_front",
        ));

        $form->getElement('bg_front')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $banner
            ))
        );

        $bg_back = $fieldset->addField("bg_back", "text", array(
            "label" => Mage::helper("productdesign")->__("Image background Back"),
            "name" => "bg_back",
        ));

        $form->getElement('bg_back')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $bg_back
            ))
        );

        $custom_bg_front = $fieldset->addField("custom_bg_front", "text", array(
            "label" => Mage::helper("productdesign")->__("Image Default Front"),
            "name" => "custom_bg_front",
        ));

        $form->getElement('custom_bg_front')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $custom_bg_front
            ))
        );

        $custom_bg_back = $fieldset->addField("custom_bg_back", "text", array(
            "label" => Mage::helper("productdesign")->__("Image Default Back"),
            "name" => "custom_bg_back",
        ));

        $form->getElement('custom_bg_back')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $custom_bg_back
            ))
        );

        $bg_upload_front = $fieldset->addField("bg_upload_front", "text", array(
            "label" => Mage::helper("productdesign")->__("Image Pattern for upload Front"),
            "name" => "bg_upload_front",
        ));

        $form->getElement('bg_upload_front')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $bg_upload_front
            ))
        );

        $bg_upload_back = $fieldset->addField("bg_upload_back", "text", array(
            "label" => Mage::helper("productdesign")->__("Image Pattern for upload Back"),
            "name" => "bg_upload_back",
        ));

        $form->getElement('bg_upload_back')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $bg_upload_back
            ))
        );
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $fieldset->addField("mod_detail", "editor", array(
            "label" => Mage::helper("productdesign")->__("Mod information"),
            "name" => "mod_detail",
            'class'=> 'textarea',
            'style'=> 'width:300% !important',
            'wysiwyg'   => true,
            'config'    => $wysiwygConfig
        ));



        $form->setValues($this->getProductDesign()->getData());

        return parent::_prepareForm();
    }

    public function getProduct(){
        return Mage::registry('current_product');
    }

    public function getProductDesign(){
        $collection = Mage::getModel('productoptions/tabproduct')->getCollection();
        $collection->addFieldToFilter('product_id',$this->getProduct()->getId());
        return $collection->getFirstItem();
    }
}