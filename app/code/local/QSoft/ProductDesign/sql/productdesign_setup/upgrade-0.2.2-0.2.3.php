<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 15/06/2016
 * Time: 09:34
 */ 
/* @var $this Mage_Eav_Model_Entity_Setup */
$installer = $this;

$installer->startSetup();

// icons
$installer->addAttribute('catalog_product','product_specification_icon' , array(
    'group'     	=> 'Specifications',
    'input'         => 'multiselect',
    'type'          => 'varchar',
    'label'         => 'Specification Icons',
    'backend'		=> "eav/entity_attribute_backend_array",
    'visible'       => 1,
    'required'		=> 0,
    'user_defined'  => 1,
    'searchable' 	=> 0,
    'filterable' 	=> 0,
    'comparable'	=> 0,
    'is_visible_on_front' => 1,
    'used_in_product_listing' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'option'        => array (
        'value' => array('optionone' => array('Option1'),
            'optiontwo' => array('Option2'),
            'optionthree' => array('Option3'),
            'optionfour' => array('Option4'),
        )
    ),
));
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'product_specification_icon');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'apply_to', QSoft_ProductDesign_Model_Product_Type::TYPE_CP_PRODUCT);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);

// feature
$installer->addAttribute('catalog_product','product_features' , array(
    'group'     	=> 'Specifications',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Features',
    'visible'       => 1,
    'required'		=> 0,
    'user_defined'  => 1,
    'searchable' 	=> 0,
    'filterable' 	=> 0,
    'comparable'	=> 0,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'product_features');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'apply_to', QSoft_ProductDesign_Model_Product_Type::TYPE_CP_PRODUCT);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_wysiwyg_enabled', 1);

// Fabric
$installer->addAttribute('catalog_product','product_fabric' , array(
    'group'     	=> 'Specifications',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Fabric',
    'visible'       => 1,
    'required'		=> 0,
    'user_defined'  => 1,
    'searchable' 	=> 0,
    'filterable' 	=> 0,
    'comparable'	=> 0,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'product_fabric');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'apply_to', QSoft_ProductDesign_Model_Product_Type::TYPE_CP_PRODUCT);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_wysiwyg_enabled', 1);

// Chart
$attr = array (
    'group'     	=> 'Specifications',
    'source' => 'productdesign/source_chart',
    'label' => 'Size Chart',
    'required' => false,
    'input' => 'select',
    'default' => 'none',
    'position' => 1,
    'sort_order' => 16,
    'visible' => 1,
    'is_system' => 1,
    'apply_to'=>'productdesign'
);
$this->addAttribute('catalog_product','product_chart',$attr);
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'product_chart');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'is_visible_on_front', 1);

$installer->endSetup();