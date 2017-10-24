<?php
/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 3:10 PM
 */


/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$this->run("--
    DROP TABLE IF EXISTS {$this->getTable('sizechart/sizechart')};
	CREATE TABLE {$this->getTable('sizechart/sizechart')} (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(300) NOT NULL DEFAULT '',
      `main_image` varchar(300) NOT NULL DEFAULT '',
      `image_cm` varchar(300) NOT NULL DEFAULT '',
      `image_inch` varchar(300) NOT NULL DEFAULT '',
      `identifier` varchar(300) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Product size chart' AUTO_INCREMENT=1 ;

");

$installer->endSetup();