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
	alter table {$this->getTable('qsoft_customer_measure')}
	    add  `type_of_measurement` int(11) null;

");



$installer->endSetup();