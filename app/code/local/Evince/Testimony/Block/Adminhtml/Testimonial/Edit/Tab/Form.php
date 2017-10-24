<?php
/**
 * Evince_Testimony extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   	Evince
 * @package		Evince_Testimony
 * @copyright  	Copyright (c) 2014
 * @license		http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Testimonial edit form tab
 *
 * @category	Evince
 * @package		Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Block_Adminhtml_Testimonial_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form{	
	/**
	 * prepare the form
	 * @access protected
	 * @return Testimony_Testimonial_Block_Adminhtml_Testimonial_Edit_Tab_Form
	 * @author Ultimate Module Creator
	 */
	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$form->setHtmlIdPrefix('testimonial_');
		$form->setFieldNameSuffix('testimonial');
		$this->setForm($form);
		$fieldset = $form->addFieldset('testimonial_form', array('legend'=>Mage::helper('testimony')->__('Testimonial')));
		$fieldset->addType('image', Mage::getConfig()->getBlockClassName('testimony/adminhtml_testimonial_helper_image'));
		$wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

		$fieldset->addField('display_order', 'text', array(
			'label' => Mage::helper('testimony')->__('Display Order'),
			'name'  => 'display_order',

		));

		$fieldset->addField('client_image', 'image', array(
			'label' => Mage::helper('testimony')->__('Image'),
			'name'  => 'client_image',

		));

		$fieldset->addField('title', 'text', array(
			'label' => Mage::helper('testimony')->__('Title'),
			'name'  => 'title',
			'required'  => true,
			'class' => 'required-entry',

		));

		$fieldset->addField('content', 'editor', array(
			'label' => Mage::helper('testimony')->__('Content'),
			'name'  => 'content',
			'config'	=> $wysiwygConfig,
			'required'  => true,
			'class' => 'required-entry',

		));

		$fieldset->addField('client_name', 'text', array(
			'label' => Mage::helper('testimony')->__('Client Name'),
			'name'  => 'client_name',
			'required'  => true,
			'class' => 'required-entry',

		));
		$fieldset->addField('status', 'select', array(
			'label' => Mage::helper('testimony')->__('Status'),
			'name'  => 'status',
			'values'=> array(
				array(
					'value' => 1,
					'label' => Mage::helper('testimony')->__('Enabled'),
				),
				array(
					'value' => 0,
					'label' => Mage::helper('testimony')->__('Disabled'),
				),
			),
		));
		if (Mage::getSingleton('adminhtml/session')->getTestimonialData()){
			$form->setValues(Mage::getSingleton('adminhtml/session')->getTestimonialData());
			Mage::getSingleton('adminhtml/session')->setTestimonialData(null);
		}
		elseif (Mage::registry('current_testimonial')){
			$form->setValues(Mage::registry('current_testimonial')->getData());
		}
		return parent::_prepareForm();
	}
}