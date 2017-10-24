<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 10/16/2015
 * Time: 3:51 PM
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->addAttribute('customer', 'location', array(
    'group'             => "General",
    'label'             => 'Location',
    'type'              => 'text',
    'input'             => 'select',
    'frontend'          => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'source'            => 'storelocator/source_location',
    'visible_on_front'  => false,
    'visible_in_advanced_search' => false,
    'unique'            => false
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'location');
$oAttribute->setData('used_in_forms', array('adminhtml_customer'));
$oAttribute->save();


$installer->endSetup();