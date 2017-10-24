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
    DROP TABLE IF EXISTS {$this->getTable('qsoft_customermeasure/measure')};
	CREATE TABLE {$this->getTable('qsoft_customermeasure/measure')} (
	    `measure_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `title` varchar(300) NOT NULL DEFAULT '',
      `video_url` varchar(300) NOT NULL DEFAULT '',
      `description` text NOT NULL,
      `unit` int(30) NOT NULL,
      `gender` int(100) NOT NULL,
      `min_value` float NOT NULL,
      `max_value` float NOT NULL,
      PRIMARY KEY (`measure_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Customer Measure Type' AUTO_INCREMENT=1 ;

");

$this->run("--
    DROP TABLE IF EXISTS {$this->getTable('qsoft_customermeasure/customer')};
	CREATE TABLE {$this->getTable('qsoft_customermeasure/customer')} (
		`id` int(10) unsigned NOT NULL auto_increment,
		`unit` varchar(255) null,
		`customer_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
		`measures` text NOT NULL,		
		PRIMARY KEY (`id`),
		KEY `CUSTOMER_ENTITY` (`customer_id`),
		CONSTRAINT `QSOFT_FK_CUSTOMER_ENTITY` FOREIGN KEY (`customer_id`) REFERENCES {$this->getTable('customer_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Customer Measure Table';

");

$installer->endSetup();