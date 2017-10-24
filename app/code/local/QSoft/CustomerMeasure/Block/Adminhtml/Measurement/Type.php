<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 3:49 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Type extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Modify header & button labels
     *
     */
    public function __construct()
    {
        $this->_controller = "adminhtml_measurement_type";
        $this->_blockGroup = "qsoft_customermeasure";
        $this->_headerText = Mage::helper("qsoft_customermeasure")->__("Manage Measurement Types");
        $this->_addButtonLabel = Mage::helper("qsoft_customermeasure")->__("Add New Measurement Type");
        parent::__construct();
    }
}