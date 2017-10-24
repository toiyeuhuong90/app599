<?php
/**
 * Created by PhpStorm.
 * User: ducns
 * Date: 1/30/2016
 * Time: 10:18 AM
 */ 
/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer = $this;

$installer->startSetup();


$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'created_at');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'frontend_label', 'Created At');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'used_in_product_listing', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'used_for_sort_by', 1);
$config = Mage::getSingleton('core/config');
$config->saveConfig('catalog/frontend/default_sort_by','created_at','default', 0);

$read = Mage::getSingleton('core/resource')->getConnection('core_read');
$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$categories = $read->fetchAll('select entity_id from catalog_category_entity where 1');
$write->query('delete from catalog_category_entity_varchar where attribute_id=66');

foreach ($categories as $category){
	$sql = 'insert into catalog_category_entity_varchar(entity_type_id, attribute_id, store_id, entity_id, value) values(?, ?, ?, ?, ?)';
	$write->query($sql, array(3, 66, 0, $category['entity_id'], 'created_at'));
}

$installer->endSetup();