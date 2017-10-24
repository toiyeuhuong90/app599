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


$installer->addAttribute('catalog_product','product_design_type' , array(
    'group'     	=> 'Images Design',
    'input'         => 'multiselect',
    'type'          => 'varchar',
    'label'         => 'Zoom type',
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
        'value' => array('optionone' => array('Front'),
            'optiontwo' => array('Left'),
            'optionthree' => array('Back'),
            'optionfour' => array('Right'),
        )
    ),
));
$attrId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'product_design_type');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'apply_to', QSoft_ProductDesign_Model_Product_Type::TYPE_CP_PRODUCT);

$sql="
ALTER TABLE `catalog_product_option_type_value` ADD `design_images` text null
";

$installer->run($sql);
$installer->endSetup();