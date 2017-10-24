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


// Gender
$installer->addAttribute('catalog_product','gender' , array(
	'group'     	=> 'Filter',
	'input'         => 'select',
	'type'          => 'int',
	'label'         => 'Gender',
	'backend'		=> "",
	'visible'       => 1,
	'required'		=> 0,
	'user_defined'  => 1,
	'searchable' 	=> 0,
	'filterable' 	=> 1,
	'comparable'	=> 0,
	'is_visible_on_front' => 1,
	'used_in_product_listing' => 1,
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
	'option'     => array (
		'values' => array(
			0 => 'Male',
			1 => 'Female',
		)
	),
));
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'gender');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'used_in_product_listing', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable_in_search', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'position', 1);


// Size
$installer->addAttribute('catalog_product','size' , array(
	'group'     	=> 'Filter',
	'input'         => 'select',
	'type'          => 'int',
	'label'         => 'Size',
	'backend'		=> "",
	'visible'       => 1,
	'required'		=> 0,
	'user_defined'  => 1,
	'searchable' 	=> 0,
	'filterable' 	=> 1,
	'comparable'	=> 0,
	'is_visible_on_front' => 1,
	'used_in_product_listing' => 1,
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
	'option'     => array (
		'values' => array(
			0 => 'S',
			1 => 'M',
			3 => 'L',
			4 => 'XL',
			5 => 'XXL',
		)
	),
));
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'size');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'used_in_product_listing', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable_in_search', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'position', 5);


// product Type
$installer->addAttribute('catalog_product','product_type' , array(
	'group'     	=> 'Filter',
	'input'         => 'select',
	'type'          => 'int',
	'label'         => 'Product Type',
	'backend'		=> "",
	'visible'       => 1,
	'required'		=> 0,
	'user_defined'  => 1,
	'searchable' 	=> 0,
	'filterable' 	=> 1,
	'comparable'	=> 0,
	'is_visible_on_front' => 1,
	'used_in_product_listing' => 1,
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
	'option'     => array (
		'values' => array(
			0 => 'Triathlon',
			1 => 'Tri Shirt',
			2 => 'Tri Suit',
			3 => 'Tri Short',
			4 => 'Cycling',
			5 => 'Football',
			6 => 'Golf',
			7 => 'Basketball',
		)
	),
));
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'product_type');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'used_in_product_listing', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable_in_search', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'position', 2);

//color
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'color');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'used_in_product_listing', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable_in_search', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'position', 3);


//price
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'price');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable', 0);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_filterable_in_search', 0);


$installer->endSetup();