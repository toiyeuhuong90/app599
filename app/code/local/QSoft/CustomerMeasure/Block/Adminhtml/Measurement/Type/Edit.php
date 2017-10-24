<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 3:57 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Type_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'qsoft_customermeasure';
        $this->_controller = 'adminhtml_measurement_type';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('qsoft_customermeasure')->__('Save Type'));
        $this->_updateButton('delete', 'label', Mage::helper('qsoft_customermeasure')->__('Delete Type'));

        $this->_formScripts[] = "
							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
    }

    public function getMeasurementTypeId()
    {
        return Mage::registry('current_measurement_type')->getId();
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_measurement_type') && Mage::registry('current_measurement_type')->getId()) {

            return Mage::helper('qsoft_customermeasure')->__('Edit "%s"', $this->htmlEscape(Mage::registry('current_measurement_type')->getTitle()));

        } else {

            return Mage::helper('qsoft_customermeasure')->__('Add Measurement Type');

        }
    }

    protected function _prepareLayout()
    {
        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('qsoft_customermeasure')->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit(\'' . $this->_getSaveAndContinueUrl() . '\')',
            'class' => 'save'
        ), 10);

        return parent::_prepareLayout();
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current' => true,
            'back' => 'edit',
            'tab' => '{{tab_id}}'
        ));
    }
}