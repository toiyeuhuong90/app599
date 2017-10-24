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

$this->run("
	ALTER TABLE {$this->getTable('qsoft_customermeasure/measure')}
	    add `show_in_dashboard` int(3) default 0;

");


$installer->endSetup();