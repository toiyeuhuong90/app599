<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 31/08/2017
 * Time: 10:49
 */ 
/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer = $this;

$installer->startSetup();

$entityTypeId     = (int)$installer->getEntityTypeId('customer');
$attributeSetId   = (int)$installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = (int)$installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

//Avatar
$installer->addAttribute('customer','avatar_customer' , array(
    'group'     	=> 'General',
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'Avatar',
    'visible'       => 1,
    'required'		=> 0,
    'user_defined'  => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
$installer->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, 'avatar_customer', 100);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'avatar_customer');
$oAttribute->setData('used_in_forms', array('adminhtml_customer'));
$oAttribute->save();

$installer->endSetup();