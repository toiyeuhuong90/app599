<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 13/04/2016
 * Time: 2:42 PM
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();


$content = <<<LOL
<h1 class="qs-page-title">About Us</h1>
<p> Test content</p>
LOL;

$data = array(
    'title' => 'About Us',
    'identifier' => 'about-us-app',
    'content' => $content,
    'is_active' => 1,
    'stores' => array(0)
);

$model = Mage::getModel('cms/block')->load($data['identifier']);
if($model->getId()){
    $model->setContent($content)
        ->save();
}else{
    Mage::getModel('cms/block')->setData($data)->save();
}

$installer->endSetup();