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
	alter table {$this->getTable('qsoft_customer_measure_customer')}
	    add  `age` int(11) null;

");

$this->run("
	alter table {$this->getTable('qsoft_customer_measure_customer')}
	    add  `gender` int(11) null;

");

$this->run("
	alter table {$this->getTable('qsoft_customer_measure_customer')}
	    add  `height` decimal(11,2) null;

");

$this->run("
	alter table {$this->getTable('qsoft_customer_measure_customer')}
	    add  `weight` decimal(11,2) null;

");

$this->run("
	alter table {$this->getTable('qsoft_customer_measure_customer')}
	    add  `bmi` VARCHAR(255) null;

");


$this->run("
	alter table {$this->getTable('qsoft_customer_measure_customer')}
	    add  `lean_mass` VARCHAR(255) null;

");
$this->run("
	alter table {$this->getTable('qsoft_customer_measure_customer')}
	    add  `body_fat` VARCHAR(255) null;

");
$this->run("
	alter table {$this->getTable('qsoft_customer_measure_customer')}
	    add  `body_weight` VARCHAR(255) null;

");


$installer->endSetup();