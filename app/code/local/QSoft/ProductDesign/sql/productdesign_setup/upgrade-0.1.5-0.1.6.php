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

$installer->getConnection()->addColumn($this->getTable('sales_flat_quote_item'), 'image_design', 'varchar(255)  NULL');
$installer->getConnection()->addColumn($this->getTable('sales_flat_order_item'), 'image_design', 'varchar(255)  NULL');

$installer->endSetup();