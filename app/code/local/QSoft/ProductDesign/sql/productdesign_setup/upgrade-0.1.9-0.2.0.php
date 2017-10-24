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

$installer->run("CREATE TABLE IF NOT EXISTS `qsoft_product_group_design` (
`id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT '0',
  `type` varchar(255) DEFAULT NULL,
  `class_html` varchar(255) DEFAULT '',
  `tooltip` text,
  `icon` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");

$installer->endSetup();