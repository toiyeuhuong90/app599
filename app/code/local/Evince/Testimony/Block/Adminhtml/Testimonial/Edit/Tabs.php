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
 * Testimonial admin edit tabs
 *
 * @category	Evince
 * @package		Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Block_Adminhtml_Testimonial_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('testimonial_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('testimony')->__('Testimonial'));
	}
	/**
	 * before render html
	 * @access protected
	 * @return Evince_Testimony_Block_Adminhtml_Testimonial_Edit_Tabs
	 * @author Ultimate Module Creator
	 */
	protected function _beforeToHtml(){
		$this->addTab('form_testimonial', array(
			'label'		=> Mage::helper('testimony')->__('Testimonial'),
			'title'		=> Mage::helper('testimony')->__('Testimonial'),
			'content' 	=> $this->getLayout()->createBlock('testimony/adminhtml_testimonial_edit_tab_form')->toHtml(),
		));
		if (!Mage::app()->isSingleStoreMode()){
			$this->addTab('form_store_testimonial', array(
				'label'		=> Mage::helper('testimony')->__('Store views'),
				'title'		=> Mage::helper('testimony')->__('Store views'),
				'content' 	=> $this->getLayout()->createBlock('testimony/adminhtml_testimonial_edit_tab_stores')->toHtml(),
			));
		}
		return parent::_beforeToHtml();
	}
}