<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 24/08/2016
 * Time: 09:27
 */
class QSoft_CustomerMeasure_Block_Account extends Mage_Core_Block_Template
{
    public function getMeasures(){
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $gender = [0];
        if($customer->getGender()){
            $gender[] =  $customer->getGender();
        }
        $collection = Mage::getModel('qsoft_customermeasure/type')->getCollection();
        $collection->addFieldToFilter('gender', array('in'=> $gender));
        return $collection;
    }
    public function getMeasuresDaskboard(){
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $gender = [0];
        if($customer->getGender()){
            $gender[] =  $customer->getGender();
        }
        $collection = Mage::getModel('qsoft_customermeasure/type')->getCollection();
        $collection->addFieldToFilter('gender', array('in'=> $gender))
        ->addFieldToFilter('show_in_dashboard',1);
        return $collection;
    }

    public function getMeasureValues(){
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $valueCollection = Mage::getModel('qsoft_customermeasure/value')->getCollection();
        $valueCollection->addFieldToFilter('customer_id', $customer->getId());
        return $valueCollection->getFirstItem();
    }
}