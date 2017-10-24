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
 * Testimonial admin block
 *
 * @category	Evince
 * @package		Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Block_Adminhtml_Testimonial extends Mage_Adminhtml_Block_Widget_Grid_Container{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function __construct(){
		$this->_controller 		= 'adminhtml_testimonial';
		$this->_blockGroup 		= 'testimony';
		$this->_headerText 		= Mage::helper('testimony')->__('Testimonial');
		$this->_addButtonLabel 	= Mage::helper('testimony')->__('Add Testimonial');
		parent::__construct();
	}
}