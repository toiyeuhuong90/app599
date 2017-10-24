<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 4:33 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Type_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("measurement_type_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("qsoft_customermeasure")->__("Measurement Type Information"));
    }

    protected function _beforeToHtml()
    {
        $this->addTab("type_info", array(
            "label" => Mage::helper("qsoft_customermeasure")->__("Type Information"),
            "title" => Mage::helper("qsoft_customermeasure")->__("Measurement Type Information"),
            "content" => $this->getLayout()->createBlock("qsoft_customermeasure/adminhtml_measurement_type_edit_tabs_form")->toHtml(),
        ));

        $this->_updateActiveTab();
        
        return parent::_beforeToHtml();
    }

    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if ($tabId) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if ($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }
}