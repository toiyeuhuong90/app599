<?php
class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Mappingfield extends Mage_Core_Block_Template
{
    public function getMeasures(){
        $gender = [0];
        if($customerId = Mage::app()->getRequest()->getParam('customer')){
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $gender[] =  $customer->getGender();
        }
        $collection = Mage::getModel('qsoft_customermeasure/type')->getCollection();
        $collection->addFieldToFilter('gender', array('in'=> $gender));
        return $collection;
    }
}