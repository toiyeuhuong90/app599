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

$sql="
ALTER TABLE `catalog_product_option_type_value` ADD `video` varchar(255) null;
";

$installer->run($sql);
$installer->endSetup();