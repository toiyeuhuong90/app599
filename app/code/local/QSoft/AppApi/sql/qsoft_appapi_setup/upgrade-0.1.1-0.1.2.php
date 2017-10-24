<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 31/08/2017
 * Time: 10:49
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS `qsoft_customer_relationship` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `parent_id` int(11),
  `customer_id` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
");

$installer->endSetup();