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


$attributes = array('price','group_price','special_price','special_from_date','special_end_date','tier_price','msrp_enabled','msrp_display_actual_price_type','msrp','tax_class_id');
foreach ($attributes as $attribute){
    $attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attribute);
    $applyTo = $this->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'apply_to');
    $this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'apply_to', $applyTo.','.QSoft_ProductDesign_Model_Product_Type::TYPE_CP_PRODUCT);
}

$installer->endSetup();