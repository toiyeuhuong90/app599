
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

$sql="
  ALTER TABLE `catalog_product_option` CHANGE `frontend_group_id` `frontend_group_id` VARCHAR(20) NULL DEFAULT NULL;
";

$installer->run($sql);

$installer->endSetup();