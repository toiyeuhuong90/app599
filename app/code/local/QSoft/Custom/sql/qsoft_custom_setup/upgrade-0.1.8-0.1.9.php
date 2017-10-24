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
<div class="qs-name-info-category">The fastest tri suit on the planet</div>
<div class="qs-list-info-category">
    <ul>
        <li>Different by design, it’s called the Maverick for a reason.</li>
        <li>We threw away the playbook and rebuilt the triathlon wetsuit from the ground up.</li>
        <li>Faster and smarter than the competition, Maverick wetsuits deliver unmatched performance.</li>
    </ul>
</div>
<div class="qs-button-info-category">
    <div class="row">
        <div class="col-sm-4 qs-col-info-category"><a href="#">Tri Shirt</a></div>
        <div class="col-sm-4 qs-col-info-category"><a href="#">Tri Suit</a></div>
        <div class="col-sm-4 qs-col-info-category"><a href="#">Tri Short</a></div>
    </div>
</div>
LOL;

$data = array(
    'title' => 'Category Search Description',
    'identifier' => 'category-search-description',
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