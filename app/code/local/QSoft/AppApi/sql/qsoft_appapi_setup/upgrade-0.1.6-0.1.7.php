<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 31/08/2017
 * Time: 10:49
 */
/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer = $this;

$installer->startSetup();
//Add thmbnail && mesh
$installer->getConnection()->addColumn($installer->getTable('qsoft_customer_measure_customer'), 'scan_method', 'varchar(255) null');
$installer->endSetup();