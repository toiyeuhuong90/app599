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

$installer->addAttribute('customer', 'allow_to_view_measurement', array(
    'group'             => "General",
    'label'             => 'Allow to view measurement',
    'type'              => 'int',
    'input'             => 'select',
    'frontend'          => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'source'            => 'eav/entity_attribute_source_boolean',
    'visible_on_front'  => false,
    'visible_in_advanced_search' => false,
    'unique'            => false
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'allow_to_view_measurement');
$oAttribute->setData('used_in_forms', array('adminhtml_customer'));
$oAttribute->save();


$installer->endSetup();