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
 * Testimonial admin edit block
 *
 * @category	Evince
 * @package		Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Block_Adminhtml_Testimonial_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
	/**
	 * constuctor
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function __construct(){
		parent::__construct();
		$this->_blockGroup = 'testimony';
		$this->_controller = 'adminhtml_testimonial';
		$this->_updateButton('save', 'label', Mage::helper('testimony')->__('Save Testimonial'));
		$this->_updateButton('delete', 'label', Mage::helper('testimony')->__('Delete Testimonial'));
		$this->_addButton('saveandcontinue', array(
			'label'		=> Mage::helper('testimony')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'		=> 'save',
		), -100);
		$this->_formScripts[] = "
			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}
	/**
	 * get the edit form header
	 * @access public
	 * @return string
	 * @author Ultimate Module Creator
	 */
	public function getHeaderText(){
		if( Mage::registry('testimonial_data') && Mage::registry('testimonial_data')->getId() ) {
			return Mage::helper('testimony')->__("Edit Testimonial '%s'", $this->htmlEscape(Mage::registry('testimonial_data')->getTitle()));
		} 
		else {
			return Mage::helper('testimony')->__('Add Testimonial');
		}
	}
}