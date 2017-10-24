<?php

class QSoft_ProductDesign_Block_Adminhtml_Frontendgroup_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        /* @var $model Mage_Cms_Model_Group */
        $model = Mage::registry('group_option_data');



        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('group_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper("productdesign")->__('Group Information')));


        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper("productdesign")->__('Name'),
            'title'     => Mage::helper("productdesign")->__('Name'),
            'required'  => true
        ));

        $icon = $fieldset->addField("icon", "text", array(
            "label" => Mage::helper("productdesign")->__("Icon"),
            "name" => "icon",
        ));

        $form->getElement('icon')->setRenderer(
            $this->getLayout()->createBlock('productdesign/adminhtml_widget_form_element_browser', '', array(
                'element' => $icon
            ))
        );


        $fieldset->addField('type', 'select', array(
            'name'      => 'type',
            'label'     => Mage::helper("productdesign")->__('Type'),
            'title'     => Mage::helper("productdesign")->__('Type'),
            'required'  => false,
            'values' => array(
                array(
                    'label' => Mage::helper("productdesign")->__('Select one'),
                    'value' => ''
                ),
                array(
                    'label' => Mage::helper("productdesign")->__('Color'),
                    'value' => 'color'
                ),
                array(
                    'label' => Mage::helper("productdesign")->__('Choose Size'),
                    'value' => 'size'
                ),
                array(
                    'label' => Mage::helper("productdesign")->__('Giving Name'),
                    'value' => 'giving_name'
                )
            )
        ));


		$fieldset->addField('sort_order', 'text', array(
            'name'      => 'sort_order',
            'label'     => Mage::helper("productdesign")->__('Sort Order'),
            'title'     => Mage::helper("productdesign")->__('Sort Order'),
            'required'  => false
        ));

        $fieldset->addField('class_html', 'text', array(
            'name'      => 'class_html',
            'label'     => Mage::helper("productdesign")->__('Class'),
            'title'     => Mage::helper("productdesign")->__('Class')
        ));


        $form->setValues($model->getData());


        $fieldset->addField('Parent', 'select', array(
            'name'      => 'parent_id',
            'label'     => Mage::helper("productdesign")->__('Parent'),
            'title'     => Mage::helper("productdesign")->__('Parent'),
            'value'     => $model->getParentId(),
            'values'	=> Mage::helper('productdesign/frontendgroup')->getSelectcat(),
            'required'  => true
        ));

        if (Mage::getSingleton("adminhtml/session")->getSliderData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getSliderData());
            Mage::getSingleton("adminhtml/session")->setSliderData(null);
        } elseif (Mage::registry("slider_data")) {
            $form->setValues(Mage::registry("slider_data")->getData());
        }

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
