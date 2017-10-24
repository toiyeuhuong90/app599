<?php
/**
 *Storelocator Extension
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://store.monyet.com/license.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to admin@monyet.com so we can mail you a copy immediately.
 *
 * @category   Magento Extensions
 * @package    Monyet_Storelocator
 * @author     Monyet <sales@monyet.com>
 * @copyright  2007-2011 Monyet
 * @license    http://store.monyet.com/license.txt
 * @version    1.0.1
 * @link       http://store.monyet.com
 */
 
class Monyet_Storelocator_Block_Adminhtml_Store_Edit_Tab_Openhours extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('openhours_form', array('legend'=>Mage::helper('storelocator')->__('Open Hours')));
     
      $fieldset->addField('monday_open', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Monday Open Time'),
          'note'      => 'format example 12:30',
          'required'  => false,
          'name'      => 'monday_open',
      ));
	  
	  $fieldset->addField('monday_close', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Monday Close Time'),
          'note'      => 'format example 24:00',
          'required'  => false,
          'name'      => 'monday_close',
      ));
	  
	  $fieldset->addField('tuesday_open', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Tuesday Open Time'),
          'required'  => false,
          'name'      => 'tuesday_open',
      ));
	  
	  $fieldset->addField('tuesday_close', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Tuesday Close Time'),
          'required'  => false,
          'name'      => 'tuesday_close',
      ));
	  
	  $fieldset->addField('wednesday_open', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Wednesday Open Time'),
          'required'  => false,
          'name'      => 'wednesday_open',
      ));
	  
	  $fieldset->addField('wednesday_close', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Wednesday Close Time'),
          'required'  => false,
          'name'      => 'wednesday_close',
      ));
	  
	  $fieldset->addField('thursday_open', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Thursday Open Time'),
          'required'  => false,
          'name'      => 'thursday_open',
      ));
	  
	  $fieldset->addField('thursday_close', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Thursday Close Time'),
          'required'  => false,
          'name'      => 'thursday_close',
      ));
	  
	  $fieldset->addField('friday_open', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Friday Open Time'),
          'required'  => false,
          'name'      => 'friday_open',
      ));
	  
	  $fieldset->addField('friday_close', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Friday Close Time'),
          'required'  => false,
          'name'      => 'friday_close',
      ));
	  
	  $fieldset->addField('saturday_open', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Saturday Open Time'),
          'required'  => false,
          'name'      => 'saturday_open',
      ));
	  
	  $fieldset->addField('saturday_close', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Saturday Close Time'),
          'required'  => false,
          'name'      => 'saturday_close',
      ));
	  
	  $fieldset->addField('sunday_open', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Sunday Open Time'),
          'required'  => false,
          'name'      => 'sunday_open',
      ));
	  
	  $fieldset->addField('sunday_close', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Sunday Close Time'),
          'required'  => false,
          'name'      => 'sunday_close',
      ));
	
     
      if ( Mage::getSingleton('adminhtml/session')->getStoreData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getStoreData());
          Mage::getSingleton('adminhtml/session')->setStoreData(null);
      } elseif ( Mage::registry('store_data') ) {
          $form->setValues(Mage::registry('store_data')->getData());
      }
      return parent::_prepareForm();
  }
}