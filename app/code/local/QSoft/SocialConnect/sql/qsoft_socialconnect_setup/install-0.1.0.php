<?php
/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 10/27/2015
 * Time: 4:49 PM
 */

/* @var $installer Mage_Customer_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->setCustomerAttributes(
    array(
        'qsoft_socialconnect_gid' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        ),
        'qsoft_socialconnect_gtoken' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        ),
        'qsoft_socialconnect_fid' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        ),
        'qsoft_socialconnect_ftoken' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        ),
        'qsoft_socialconnect_tid' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        ),
        'qsoft_socialconnect_ttoken' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        ),
        'qsoft_socialconnect_lid' => array(
            'type' => 'text',
            'visible' => false,
            'required' => false,
            'user_defined' => false
        ),
        'qsoft_socialconnect_ltoken' => array(
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