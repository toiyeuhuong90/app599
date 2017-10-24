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

$installer->addAttribute('customer', 'interested', array(
    'group'             => "General",
    'label'             => 'Interested',
    'type'              => 'text',
    'input'             => 'multiselect',
    'frontend'          => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'source'            => 'qsoft_customer/source_interested',
    'visible_on_front'  => false,
    'visible_in_advanced_search' => false,
    'unique'            => false
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'interested');
$oAttribute->setData('used_in_forms', array('customer_account_edit','customer_account_create','adminhtml_customer','checkout_register'));
$oAttribute->save();


$installer->endSetup();