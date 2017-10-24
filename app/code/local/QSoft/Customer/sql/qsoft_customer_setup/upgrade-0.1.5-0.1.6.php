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

$installer->addAttribute('customer', 'weight', array(
    'group'             => "General",
    'label'             => 'Weight',
    'type'              => 'varchar',
    'input'             => 'text',
    'frontend'          => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'weight');
$oAttribute->setData('used_in_forms', array('customer_account_edit','adminhtml_customer'));
$oAttribute->save();


$installer->addAttribute('customer', 'type_weight', array(
    'group'             => "General",
    'label'             => 'Weight type',
    'type'              => 'varchar',
    'input'             => 'text',
    'frontend'          => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'type_weight');
$oAttribute->setData('used_in_forms', array('customer_account_edit','adminhtml_customer'));
$oAttribute->save();



$installer->addAttribute('customer', 'height', array(
    'group'             => "General",
    'label'             => 'Height',
    'type'              => 'varchar',
    'input'             => 'text',
    'frontend'          => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'height');
$oAttribute->setData('used_in_forms', array('customer_account_edit','adminhtml_customer'));
$oAttribute->save();



$installer->addAttribute('customer', 'unit_measurements', array(
    'group'             => "General",
    'label'             => 'Unit Measurements',
    'type'              => 'varchar',
    'input'             => 'text',
    'frontend'          => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'unit_measurements');
$oAttribute->setData('used_in_forms', array('customer_account_edit','adminhtml_customer'));
$oAttribute->save();


$installer->endSetup();