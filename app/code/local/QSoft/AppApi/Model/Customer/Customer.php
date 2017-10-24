<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 27/09/2017
 * Time: 15:07
 */ 
class QSoft_AppApi_Model_Customer_Customer extends Mage_Customer_Model_Customer {
    public function getLocation(){
        if($location = $this->getData('location')){
            $location = Mage::getModel('storelocator/store')->load($location);
            return $location->getData();
        }
        return [];
    }
}