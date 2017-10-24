<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 10/16/2015
 * Time: 3:51 PM
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
//{tokenId:"tokenId", email:"email", frstname:"frstname", lastname:"lastname", typeLogin:"typeLogin", avatar:"avatar"}
$installer->startSetup();

$installer->addAttribute('customer', 'social_token', array(
    'group'             => "General",
    'label'             => 'Social token ID',
    'type'              => 'varchar',
    'input'             => 'text',
    'frontend'          => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'social_token');
$oAttribute->setData('used_in_forms', array('customer_account_edit','adminhtml_customer'));
$oAttribute->save();


$installer->addAttribute('customer', 'social_type', array(
    'group'             => "General",
    'label'             => 'Social type',
    'type'              => 'varchar',
    'input'             => 'text',
    'frontend'          => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true
));


$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'social_type');
$oAttribute->setData('used_in_forms', array('customer_account_edit','adminhtml_customer'));
$oAttribute->save();

$installer->endSetup();