<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('storelocator_store')};
	CREATE TABLE {$this->getTable('storelocator_store')} (
	  `store_id` int(11) unsigned NOT NULL auto_increment,
	  `store_name` varchar(255) NOT NULL,
	  `store_manager` varchar(255) NOT NULL,
	  `store_email` varchar(255) NOT NULL,
	  `store_phone` varchar(20) NOT NULL,
	  `store_fax` varchar(20) NOT NULL,
	  `preferred_contact` tinyint(1) NOT NULL,
	  `description` text NOT NULL,
	  `status` smallint(6) NOT NULL default '0',
	  `address` text NOT NULL,
	  `address_2` text NOT NULL,
	  `state` varchar(255) NOT NULL,
	  `suburb` varchar(255) NOT NULL,
	  `city` varchar(255) NOT NULL,
	  `region_id` INT( 10 ) UNSIGNED NOT NULL,
	  `city_id` INT( 10 ) UNSIGNED NOT NULL,
	  `suburb_id` INT( 10 ) UNSIGNED NOT NULL,  
	  `zipcode` varchar(255) NOT NULL,
	  `state_id` int(11) NOT NULL,
	  `country` varchar(255) NOT NULL,
	  `store_latitude` varchar(20) NOT NULL,
	  `store_longitude` varchar(20) NOT NULL,
	  `monday_open` varchar(5) NOT NULL,
	  `monday_close` varchar(5) NOT NULL,
	  `tuesday_open` varchar(5) NOT NULL,
	  `tuesday_close` varchar(5) NOT NULL,
	  `wednesday_open` varchar(5) NOT NULL,
	  `wednesday_close` varchar(5) NOT NULL,
	  `thursday_open` varchar(5) NOT NULL,
	  `thursday_close` varchar(5) NOT NULL,
	  `friday_open` varchar(5) NOT NULL,
	  `friday_close` varchar(5) NOT NULL,
	  `saturday_open` varchar(5) NOT NULL,
	  `saturday_close` varchar(5) NOT NULL,
	  `sunday_open` varchar(5) NOT NULL,
	  `sunday_close` varchar(5) NOT NULL,
	  `minimum_gap` int(11) NOT NULL default '45',
	  PRIMARY KEY  (`store_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
	
	DROP TABLE IF EXISTS `{$this->getTable('storelocator_store_store')}`;
	CREATE TABLE `{$this->getTable('storelocator_store_store')}` (
	  `localstore_id` INT(10) UNSIGNED NOT NULL,
	  `store_ids` SMALLINT(5) UNSIGNED NOT NULL,
	  PRIMARY KEY (`localstore_id`,`store_ids`)
	) ENGINE=InnoDB;

");

$installer->endSetup(); 