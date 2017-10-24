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
 
class Monyet_Storelocator_Block_Adminhtml_Store_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('store_form', array('legend'=>Mage::helper('storelocator')->__('Store information')));
     
	 
      $fieldset->addField('store_name', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Store Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'store_name',
      ));
	  /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'multiselect', 
                    array (
                            'name' => 'stores[]', 
                            'label' => Mage::helper('cms')->__('Store view'), 
                            'title' => Mage::helper('cms')->__('Store view'), 
                            'required' => true, 
                            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true) ));
        }
        else {
            $fieldset->addField('store_ids', 'hidden', array (
                    'name' => 'stores[]', 
                    'value' => Mage::app()->getStore(true)->getId() ));
        }
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('storelocator')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('storelocator')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('storelocator')->__('Disabled'),
              ),
          ),
      ));	  
	  
	   $fieldset->addField('address', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Address'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'address',
		  'style'     => 'width:500px;',
      ));		
	  

      $fieldset->addField('zipcode', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Zipcode'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'zipcode',
      ));  	

	  $fieldset->addField('country', 'select', array(
          'label'     => Mage::helper('storelocator')->__('Country'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'country',
		  'values'    => Mage::helper('storelocator')->getOptionCountry(),
      ));	
	
	  $fieldset->addField('stateEl', 'note', array(
          'label'     => Mage::helper('storelocator')->__('State/Province'),
          'required'  => true,
		  'name'	  => 'stateEl',
		  'text'	  => $this->getLayout()->createBlock('storelocator/adminhtml_region')->setTemplate('monyet/storelocator/region.phtml')->toHtml(),
	 ));	
	
	  $fieldset->addField('store_longitude', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Store Longitude'),
          'name'      => 'store_longitude',
		  'note'      => Mage::helper('storelocator')->__('If empty, will attempt to retrieve using the geo location address.'),
      ));	 	
		
	  $fieldset->addField('store_latitude', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Store Latitude'),
          'name'      => 'store_latitude',
      )); 
	  	  
     
      $fieldset->addField('description', 'textarea', array(
          'name'      => 'description',
          'label'     => Mage::helper('storelocator')->__('Description'),
          'title'     => Mage::helper('storelocator')->__('Description'),
          'style'     => 'width:500px; height:150px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));

      $fieldset = $form->addFieldset('store_form_contact', array('legend'=>Mage::helper('storelocator')->__('Contact information')));

      $fieldset->addField('store_manager', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Store Manager'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'store_manager',
      ));

      $fieldset->addField('store_phone', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Phone Number'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'store_phone',
      ));

      $fieldset->addField('store_email', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Email Address'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'store_email',
      ));

      $fieldset->addField('store_fax', 'text', array(
          'label'     => Mage::helper('storelocator')->__('Fax Number'),
          'name'      => 'store_fax',
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