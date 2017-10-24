<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 4:36 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Type_Edit_Tabs_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('measurement_type_form', array('legend' => Mage::helper('qsoft_customermeasure')->__('Measurement Type Information')));

        /* @var $type QSoft_CustomerMeasure_Model_Type */
        $type = Mage::registry('current_measurement_type');


        $fieldset->addField('measure_id', 'hidden',
            array(
                'name' => 'measure_id',
            )
        );

        $fieldset->addField('title', 'text',
            array(
                'name' => 'title',
                'label' => Mage::helper('qsoft_customermeasure')->__('Measurement Type Title'),
                'title' => Mage::helper('qsoft_customermeasure')->__('Measurement Type Title'),
                'note' => Mage::helper('qsoft_customermeasure')->__('Maximum length must be less then %s symbols', 300),
                'required' => true,
            )
        );

        $type = $fieldset->addField('unit', 'select', array(
            'label' => Mage::helper('qsoft_customermeasure')->__('Type of Unit'),
            'name' => 'unit',
            'values' => array('1' => $this->__('Weight'), '2' => $this->__('Height'))
        ));

        $fieldset->addField('type_of_measurement', 'select', array(
            'label' => Mage::helper('qsoft_customermeasure')->__('Type of measurement'),
            'name' => 'type_of_measurement',
            'required' => true,
            'values' => array('0'=>'-- Select one --','1' => $this->__('Fitness'), '2' => $this->__('Garment'))
        ));

        $fieldset->addField('max_value', 'text',
            array(
                'name' => 'max_value',
                'label' => Mage::helper('qsoft_customermeasure')->__('Max value'),
                'required' => true,
            )
        );

        $fieldset->addField('min_value', 'text',
            array(
                'name' => 'min_value',
                'label' => Mage::helper('qsoft_customermeasure')->__('Min value'),
                'required' => true,
            )
        );

        $type = $fieldset->addField('gender', 'select', array(
            'label' => Mage::helper('qsoft_customermeasure')->__('Type of Gender'),
            'name' => 'gender',
            'values' => array('0' => $this->__('Both'), '1' => $this->__('Male'), '2' => $this->__('Female'))
        ));





        $fieldset->addField('video_url', 'text', array(
            'label' => Mage::helper('qsoft_customermeasure')->__('Youtube Url'),
            'name' => 'video_url',
        ));

        $fieldset->addField('description', 'textarea',
            array(
                'name' => 'description',
                'label' => Mage::helper('qsoft_customermeasure')->__('Description'),
                'title' => Mage::helper('qsoft_customermeasure')->__('Description'),
            )
        );

        $fieldset->addField('show_in_dashboard', 'select', array(
            'label' => Mage::helper('qsoft_customermeasure')->__('Show in dashboard'),
            'name' => 'show_in_dashboard',
            'values' => array('0' => $this->__('No'), '1' => $this->__('Yes'))
        ));

        if (Mage::getSingleton('adminhtml/session')->getMeasurementTypeData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getMeasurementTypeData());
            Mage::getSingleton('adminhtml/session')->setMeasurementTypeData(null);
        } elseif (Mage::registry('current_measurement_type')) {
            $form->setValues(Mage::registry('current_measurement_type')->getData());
        }

        $this->setForm($form);



        return parent::_prepareForm();

    }
}