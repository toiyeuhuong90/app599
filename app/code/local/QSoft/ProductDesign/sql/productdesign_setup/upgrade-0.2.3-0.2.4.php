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

$installer->getConnection()->addColumn($this->getTable('cms/block'), 'is_size_chart', 'int(1)  NULL');

$installer->endSetup();