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
 * Testimonial model
 *
 * @category	Evince
 * @package		Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Model_Testimonial extends Mage_Core_Model_Abstract{
	/**
	 * Entity code.
	 * Can be used as part of method name for entity processing
	 */
	const ENTITY= 'testimony_testimonial';
	const CACHE_TAG = 'testimony_testimonial';
	/**
	 * Prefix of model events names
	 * @var string
	 */
	protected $_eventPrefix = 'testimony_testimonial';
	
	/**
	 * Parameter name in event
	 * @var string
	 */
	protected $_eventObject = 'testimonial';
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function _construct(){
		parent::_construct();
		$this->_init('testimony/testimonial');
	}
	/**
	 * before save testimonial
	 * @access protected
	 * @return Evince_Testimony_Model_Testimonial
	 * @author Ultimate Module Creator
	 */
	protected function _beforeSave(){
		parent::_beforeSave();
		$now = Mage::getSingleton('core/date')->gmtDate();
		if ($this->isObjectNew()){
			$this->setCreatedAt($now);
		}
		$this->setUpdatedAt($now);
		return $this;
	}
	/**
	 * get the url to the testimonial details page
	 * @access public
	 * @return string
	 * @author Ultimate Module Creator
	 */
	public function getTestimonialUrl(){
		return Mage::getUrl('testimony/testimonial/view', array('id'=>$this->getId()));
	}
	/**
	 * get the testimonial Content
	 * @access public
	 * @return string
	 * @author Ultimate Module Creator
	 */
	public function getContent(){
		$content = $this->getData('content');
		$helper = Mage::helper('cms');
		$processor = $helper->getBlockTemplateProcessor();
		$html = $processor->filter($content);
		return $html;
	}
	/**
	 * save testimonial relation
	 * @access public
	 * @return Evince_Testimony_Model_Testimonial
	 * @author Ultimate Module Creator
	 */
	protected function _afterSave() {
		return parent::_afterSave();
	}
}