<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 15/06/2016
 * Time: 09:34
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$attr = array (
    'group'     	=> 'Images Design',
    'source' => 'productdesign/source_groupoption',
    'label' => 'Group Option',
    'required' => false,
    'input' => 'select',
    'default' => 'none',
    'position' => 1,
    'sort_order' => 16,
    'visible' => 1,
    'is_system' => 1,
    'apply_to'=>'productdesign'
);
$this->addAttribute('catalog_product','product_frontend_group',$attr);
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'product_frontend_group');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);

$installer->endSetup();