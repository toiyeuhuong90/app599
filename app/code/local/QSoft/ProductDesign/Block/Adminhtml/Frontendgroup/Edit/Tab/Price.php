<?php

class Qsoft_productdesign_Block_Adminhtml_Frontendgroup_Edit_Tab_Price
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        /* @var $model Mage_Cms_Model_Group */
        $model = Mage::registry('group_option_data');



        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('group_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper("productdesign")->__('Group Information')));

        $fieldset->addType('price_config','SM_productdesign_Block_Adminhtml_GroupOption_Edit_Tab_Price_Element_Price');

        //print_r($model->getPriceConfig());die;

        $fieldset->addField('price_type', 'select', array(
            'label' => Mage::helper("productdesign")->__('Price Type'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'price_type',
            'value' => $model->getPriceType(),
            'values' => array(
                array(
                   'label' => $this->__('Apply Main Price'),
                   'value' => 'main_price'
                ),
                array(
                    'label' => $this->__('Add Price'),
                    'value' => 'add_price'
                ),
                array(
                    'label' => $this->__('Normal Price'),
                    'value' => 'normal_price'
                )
            )
        ));

        $fieldset->addField('option_ids', 'multiselect', array(
            'label' => Mage::helper("productdesign")->__('Option Config'),
            'name' => 'option_ids',
            'value' => $model->getOptionIds(),
            'values' => Mage::helper('productdesign/frontendgroup')->getEffectOptionsArray()
        ));

        $fieldset->addField('price_config', 'price_config', array(
            'label' => Mage::helper("productdesign")->__('Price Config'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'price_config',
            'value' => $model->getPriceConfig()
        ));

        //$form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper("productdesign")->__('Group Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper("productdesign")->__('Group Information');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}
