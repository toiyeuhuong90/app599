<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 01/08/2016
 * Time: 17:07
 */ 
class QSoft_Customer_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getAdditionAddressJson($_pAddsses){
        $result = array();
        foreach($_pAddsses as $key=>$address){
            $data = $address->getData();
            $data['street1'] = $address->getStreet(1);
            $data['street2'] = $address->getStreet(2);
            $result[$key] = $data;
        }
        return Mage::helper('core')->jsonEncode($result);
    }
}