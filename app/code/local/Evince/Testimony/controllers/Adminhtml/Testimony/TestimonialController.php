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
 * Testimonial admin controller
 *
 * @category	Evince
 * @package		Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Adminhtml_Testimony_TestimonialController extends Evince_Testimony_Controller_Adminhtml_Testimony{
	/**
	 * init the testimonial
	 * @access protected
	 * @return Evince_Testimony_Model_Testimonial
	 */
	protected function _initTestimonial(){
		$testimonialId  = (int) $this->getRequest()->getParam('id');
		$testimonial	= Mage::getModel('testimony/testimonial');
		if ($testimonialId) {
			$testimonial->load($testimonialId);
		}
		Mage::register('current_testimonial', $testimonial);
		return $testimonial;
	}
 	/**
	 * default action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function indexAction() {
		$this->loadLayout();
		$this->_title(Mage::helper('testimony')->__('Testimony'))
			 ->_title(Mage::helper('testimony')->__('Testimonial'));
		$this->renderLayout();
	}
	/**
	 * grid action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function gridAction() {
		$this->loadLayout()->renderLayout();
	}
	/**
	 * edit testimonial - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function editAction() {
		$testimonialId	= $this->getRequest()->getParam('id');
		$testimonial  	= $this->_initTestimonial();
		if ($testimonialId && !$testimonial->getId()) {
			$this->_getSession()->addError(Mage::helper('testimony')->__('This testimonial no longer exists.'));
			$this->_redirect('*/*/');
			return;
		}
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$testimonial->setData($data);
		}
		Mage::register('testimonial_data', $testimonial);
		$this->loadLayout();
		$this->_title(Mage::helper('testimony')->__('Testimony'))
			 ->_title(Mage::helper('testimony')->__('Testimonial'));
		if ($testimonial->getId()){
			$this->_title($testimonial->getTitle());
		}
		else{
			$this->_title(Mage::helper('testimony')->__('Add testimonial'));
		}
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) { 
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true); 
		}
		$this->renderLayout();
	}
	/**
	 * new testimonial action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function newAction() {
		$this->_forward('edit');
	}
	/**
	 * save testimonial - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function saveAction() {
		if ($data = $this->getRequest()->getPost('testimonial')) {
			try {
				$testimonial = $this->_initTestimonial();
				$testimonial->addData($data);
				$client_imageName = $this->_uploadAndGetName('client_image', Mage::helper('testimony/testimonial_image')->getImageBaseDir(), $data);
				$testimonial->setData('client_image', $client_imageName);
				$testimonial->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('testimony')->__('Testimonial was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $testimonial->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} 
			catch (Mage_Core_Exception $e){
				if (isset($data['client_image']['value'])){
					$data['client_image'] = $data['client_image']['value'];
				}
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
			catch (Exception $e) {
				Mage::logException($e);
				if (isset($data['client_image']['value'])){
					$data['client_image'] = $data['client_image']['value'];
				}
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimony')->__('There was a problem saving the testimonial.'));
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimony')->__('Unable to find testimonial to save.'));
		$this->_redirect('*/*/');
	}
	/**
	 * delete testimonial - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0) {
			try {
				$testimonial = Mage::getModel('testimony/testimonial');
				$testimonial->setId($this->getRequest()->getParam('id'))->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('testimony')->__('Testimonial was successfully deleted.'));
				$this->_redirect('*/*/');
				return; 
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimony')->__('There was an error deleteing testimonial.'));
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				Mage::logException($e);
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimony')->__('Could not find testimonial to delete.'));
		$this->_redirect('*/*/');
	}
	/**
	 * mass delete testimonial - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function massDeleteAction() {
		$testimonialIds = $this->getRequest()->getParam('testimonial');
		if(!is_array($testimonialIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimony')->__('Please select testimonial to delete.'));
		}
		else {
			try {
				foreach ($testimonialIds as $testimonialId) {
					$testimonial = Mage::getModel('testimony/testimonial');
					$testimonial->setId($testimonialId)->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('testimony')->__('Total of %d testimonial were successfully deleted.', count($testimonialIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimony')->__('There was an error deleteing testimonial.'));
				Mage::logException($e);
			}
		}
		$this->_redirect('*/*/index');
	}
	/**
	 * mass status change - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function massStatusAction(){
		$testimonialIds = $this->getRequest()->getParam('testimonial');
		if(!is_array($testimonialIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimony')->__('Please select testimonial.'));
		} 
		else {
			try {
				foreach ($testimonialIds as $testimonialId) {
				$testimonial = Mage::getSingleton('testimony/testimonial')->load($testimonialId)
							->setStatus($this->getRequest()->getParam('status'))
							->setIsMassupdate(true)
							->save();
				}
				$this->_getSession()->addSuccess($this->__('Total of %d testimonial were successfully updated.', count($testimonialIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimony')->__('There was an error updating testimonial.'));
				Mage::logException($e);
			}
		}
		$this->_redirect('*/*/index');
	}
	/**
	 * export as csv - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function exportCsvAction(){
		$fileName   = 'testimonial.csv';
		$content	= $this->getLayout()->createBlock('testimony/adminhtml_testimonial_grid')->getCsv();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	/**
	 * export as MsExcel - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function exportExcelAction(){
		$fileName   = 'testimonial.xls';
		$content	= $this->getLayout()->createBlock('testimony/adminhtml_testimonial_grid')->getExcelFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	/**
	 * export as xml - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function exportXmlAction(){
		$fileName   = 'testimonial.xml';
		$content	= $this->getLayout()->createBlock('testimony/adminhtml_testimonial_grid')->getXml();
		$this->_prepareDownloadResponse($fileName, $content);
	}
}