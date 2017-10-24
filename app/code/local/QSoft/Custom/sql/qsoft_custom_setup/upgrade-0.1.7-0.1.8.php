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

// cart page
$content = <<<LOL
<div class="qs-banner-page" data-parallax="scroll" data-image-src="{{media url="wysiwyg/shoppingcart-bg.jpg"}}">&nbsp;</div>
LOL;

$data = array(
    'title' => 'Banner on cart page',
    'identifier' => 'cart_banner',
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


// dashboard page
$content = <<<LOL
<div style="display:none" id="banner-for-male"  data-parallax="scroll" data-image-src="{{media url="wysiwyg/mydashboard-man-bg.jpg"}}">&nbsp;</div>
<div style="display:none" id="banner-for-female" data-parallax="scroll" data-image-src="{{media url="wysiwyg/mydashboard-woman-bg.jpg"}}">&nbsp;</div>
LOL;

$data = array(
    'title' => 'Banner on Dashboard',
    'identifier' => 'customer_account_banner',
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

// myaccount_edit_banner page
$content = <<<LOL
<div class="qs-banner-page" data-parallax="scroll" data-image-src="{{media url="wysiwyg/myaccount-bg.jpg"}}">&nbsp;</div>
LOL;

$data = array(
    'title' => 'Banner on Account Information page',
    'identifier' => 'myaccount_edit_banner',
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

// measure_banner page
$content = <<<LOL
<div class="qs-banner-page" data-parallax="scroll" data-image-src="{{media url="wysiwyg/mymeasurement-bg.jpg"}}">&nbsp;</div>
LOL;

$data = array(
    'title' => 'Banner on measure page',
    'identifier' => 'measure_banner',
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

// wishlist_banner page
$content = <<<LOL
<div class="qs-banner-page" data-parallax="scroll" data-image-src="{{media url="wysiwyg/mydesign-bg.jpg"}}">&nbsp;</div>
LOL;

$data = array(
    'title' => 'Banner on my design page',
    'identifier' => 'wishlist_banner',
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


// history_order_banner page
$content = <<<LOL
<div class="qs-banner-page" data-parallax="scroll" data-image-src="{{media url="wysiwyg/myorder-bg.jpg"}}">&nbsp;</div>
LOL;

$data = array(
    'title' => 'Banner on my order page',
    'identifier' => 'history_order_banner',
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