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
 
class Monyet_Storelocator_Block_Adminhtml_Store_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('storelocator_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('storelocator')->__('Store Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('storelocator')->__('General Information'),
          'title'     => Mage::helper('storelocator')->__('General Information'),
          'content'   => $this->getLayout()->createBlock('storelocator/adminhtml_store_edit_tab_form')->toHtml(),
      ));
//
//      $this->addTab('contact_section', array(
//          'label'     => Mage::helper('storelocator')->__('Contact Information'),
//          'title'     => Mage::helper('storelocator')->__('Contact Information'),
//          'content'   => $this->getLayout()->createBlock('storelocator/adminhtml_store_edit_tab_contactform')->toHtml(),
//      ));
//
//	  $this->addTab('openhours_section', array(
//          'label'     => Mage::helper('storelocator')->__('Open Hours'),
//          'title'     => Mage::helper('storelocator')->__('Open Hours'),
//          'content'   => $this->getLayout()->createBlock('storelocator/adminhtml_store_edit_tab_openhours')->toHtml(),
//      ));
//
      return parent::_beforeToHtml();
  }
}