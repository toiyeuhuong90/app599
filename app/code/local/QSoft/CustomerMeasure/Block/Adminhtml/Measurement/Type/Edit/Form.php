<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 4:45 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Type_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form for render
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            )
        );

        /* @var $type QSoft_CustomerMeasure_Model_Type */
        $type = Mage::registry('current_measurement_type');

        if ($type->getId()) {
            $form->addField('measure_id', 'hidden', array(
                'name' => 'measure_id',
            ));
            $form->setValues($type->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}