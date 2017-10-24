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
 * Testimonial view block
 *
 * @category	Evince
 * @package		Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Block_Testimonial_View extends Mage_Core_Block_Template{
	/**
	 * get the current testimonial
	 * @access public
	 * @return mixed (Evince_Testimony_Model_Testimonial|null)
	 * @author Ultimate Module Creator
	 */
	public function getCurrentTestimonial(){
		return Mage::registry('current_testimony_testimonial');
	}
} 