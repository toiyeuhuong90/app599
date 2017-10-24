
<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 01/08/2016
 * Time: 17:07
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();


$content = <<<LOL
<img src="{{media url="wysiwyg/schedule_page.jpg"}}" alt="Schedule for body scan" />
LOL;

$data = array(
    'title' => 'Schedule Banner',
    'identifier' => 'schedule_banner',
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
<h1 class="qs-page-title">Schedule A Body Scan</h1>
        <div class="qs-desc-14">Safe and extremely accurate, our 3D body scanner can take all your measurement<br />in just over 1 second and will save them automatically.</div>
        <div class="qs-desc-16">Get the 3d scanner experience by complete form below.</div>
LOL;

$data = array(
    'title' => 'Schedule Info',
    'identifier' => 'schedule_info',
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