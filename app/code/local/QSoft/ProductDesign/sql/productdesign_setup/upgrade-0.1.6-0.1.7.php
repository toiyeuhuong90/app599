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


$installer->getConnection()->addColumn($this->getTable('catalog_product_option'), 'coordinate_top', 'int(4)  NULL');
$installer->getConnection()->addColumn($this->getTable('catalog_product_option'), 'coordinate_left', 'int(4)  NULL');

$installer->endSetup();