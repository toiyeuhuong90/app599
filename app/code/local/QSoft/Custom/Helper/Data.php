<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 13/04/2016
 * Time: 2:42 PM
 */ 
class QSoft_Custom_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getVnProvince(){
        $read = Mage::getModel('core/resource')->getConnection('core_read');
        $sql = 'select * from viettelpost_province where 1';
        $city = $read->fetchAll($sql);
        $districts = array();
        $wards = array();
        foreach($city as $item){
            $sql = 'select * from viettelpost_district where city_id= ?';
            $district =  $read->fetchAll($sql, array($item['id']));
            foreach($district as $v){
                $districts[$item['state_id']][] = array(
                    'value'=>$v['id'],
                    'label'=>$v['name'],
                );
                //ward
                $sql = 'select * from viettelpost_ward where district_id=?';
                $ward =  $read->fetchAll($sql,array($v['id']));
                foreach($ward as $sv){
                    $wards[$v['id']][] = array(
                        'value'=>$sv['id'],
                        'label'=>$sv['name'],
                        'districtLabel'=>$v['name'],
                    );
                }
            }
        }
        return Mage::helper('core')->jsonEncode(array('district'=>$districts, 'ward'=>$wards));
    }
    public function getProvinceTypeToOptionArray(){
        $read = Mage::getModel('core/resource')->getConnection('core_read');
        $sql = 'select * from viettelpost_province where 1';
        $cities = $read->fetchAll($sql);
        $result = array();
        foreach ($cities as $item) {
            $result[$item['id']] = $item['name'];
        }
        return $result;
    }
}