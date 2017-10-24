<?php
/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 11/17/2015
 * Time: 10:32 AM
 */

/* @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

$installer->setCustomerAttributes(
    array(
        'qsoft_socialconnect_vkid' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        ),
        'qsoft_socialconnect_vktoken' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        )
    )
);

// Install our custom attributes
$installer->installCustomerAttributes();


$installer->endSetup();