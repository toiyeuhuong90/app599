<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 10/16/2015
 * Time: 3:51 PM
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
//{tokenId:"tokenId", email:"email", frstname:"frstname", lastname:"lastname", typeLogin:"typeLogin", avatar:"avatar"}
$installer->startSetup();

$attrId = $installer->getAttributeId('customer', 'social_token');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $attrId, 'backend_type', 'text');
$installer->endSetup();