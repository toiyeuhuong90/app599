<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 13/04/2016
 * Time: 2:42 PM
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

$content = <<<LOL
<ul class="qs-list-social">
    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
    <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
    <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
    <li><a href="#"><i class="fa fa-vimeo" aria-hidden="true"></i></a></li>
    <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
</ul>
LOL;

$data = array(
    'title' => 'Social Links',
    'identifier' => 'social_links',
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

$content = <<<LOL
<ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Shipping Policy</a></li>
            <li><a href="#">Return & Order</a></li>
            <li><a href="#">Private Policy</a></li>
        </ul>
LOL;

$data = array(
    'title' => 'Footer Links',
    'identifier' => 'footer_links',
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
$config = Mage::getSingleton('core/config');
$config->saveConfig('design/footer/copyright','Copyright by @Escapevelocity 2016','default', 0);
$config->saveConfig('design/header/logo_src','images/logo.png','default', 0);
$config->saveConfig('design/header/logo_src_small','images/logo_scroll.png','default', 0);
$config->saveConfig('design/package/name','rwd','default', 0);
$config->saveConfig('design/theme/locale','velocity','default', 0);
$config->saveConfig('design/theme/template','velocity','default', 0);
$config->saveConfig('design/theme/skin','velocity','default', 0);
$config->saveConfig('design/theme/layout','velocity','default', 0);

$installer->endSetup();