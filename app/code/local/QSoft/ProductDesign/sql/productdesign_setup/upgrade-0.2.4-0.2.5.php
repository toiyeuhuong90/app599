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


$installer->run("CREATE TABLE IF NOT EXISTS `{$installer->getTable('productdesign/inspireme')}` (
                    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
                    `product_id` int(11) NOT NULL,
                    `name` varchar(255) NOT NULL,
                    `sort_order` int(11) DEFAULT '0',
                    `avartar_image` varchar(255) DEFAULT '',
                    `frontend_group_id` int(11) NOT NULL,
                    `description` text null,
                    `product_options_json` text CHARACTER SET utf8
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ");

$installer->endSetup();