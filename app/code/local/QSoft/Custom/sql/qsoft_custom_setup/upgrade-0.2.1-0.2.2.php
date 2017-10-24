<?php
/**
 * Created by PhpStorm.
 * User: ducns
 * Date: 1/30/2016
 * Time: 10:18 AM
 */ 
/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer = $this;
$helper = Mage::helper('core');
$installer->startSetup();
//province
$sql="
CREATE TABLE IF NOT EXISTS `viettelpost_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) NOT NULL,
  `vt_province_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$installer->run($sql);


//district
$sql="
CREATE TABLE IF NOT EXISTS `viettelpost_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `vt_province_id` int(11) NOT NULL,
  `vt_district_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11)  NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$installer->run($sql);

//ward
$sql="
CREATE TABLE IF NOT EXISTS `viettelpost_ward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vt_ward_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,  
  `vt_district_id` int(11) NOT NULL,  
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11)  NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$installer->run($sql);

$write = Mage::getSingleton('core/resource')->getConnection('core_write');
$province = file_get_contents('http://api.vtp.vn/api/province');
$cities = $helper->jsonDecode($province);
$k = 485;
foreach($cities['_embedded']['province'] as $city){
    $code = Mage::getSingleton('catalog/product_url')->formatUrlKey($city['Name']);
    $sql = 'insert into directory_country_region (country_id,code,default_name) values(?,?,?)';
    $write->query($sql, array('VN', $code, $city['Name']));
    $stateId = $write->lastInsertId();

    //province
    $sql = 'insert into viettelpost_province (state_id,vt_province_id,name,position) values(?,?,?,?)';
    $write->query($sql, array($stateId, $city['ProvinceId'], $city['Name'], 0));
    $cityId = $write->lastInsertId();

    //district
    $pdistrictData = file_get_contents('http://api.vtp.vn/api/district?province='.$city['ProvinceId']);
    $districts = $helper->jsonDecode($pdistrictData);
    foreach ($districts['_embedded']['district'] as $district){
        $sql = 'insert into viettelpost_district (city_id,vt_province_id,vt_district_id,name,position) values(?,?,?,?,?)';
        $write->query($sql, array($cityId, $city['ProvinceId'], $district['DistrictId'], $district['Name'], 0));
        $districtId = $write->lastInsertId();

        //ward
        $wardData = file_get_contents('http://api.vtp.vn/api/ward?district='.$district['DistrictId']);
        $wards = $helper->jsonDecode($wardData);
        foreach ($wards['_embedded']['ward'] as $ward){
            $sql = 'insert into viettelpost_ward (vt_ward_id,district_id,vt_district_id,name,position) values(?,?,?,?,?)';
            $write->query($sql, array( $ward['WardId'],$districtId, $district['DistrictId'], $ward['Name'], 0));
        }
    }
}
//echo '<pre>';
//var_dump($helper->jsonDecode($cities));
//die();
$installer->endSetup();