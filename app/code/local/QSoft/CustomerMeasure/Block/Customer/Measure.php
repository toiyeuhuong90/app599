<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 2:19 PM
 */
class QSoft_CustomerMeasure_Block_Customer_Measure extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('qsoft/customer/measure/profile.phtml');

//        $this->_collection = Mage::getModel('q')->getProductCollection();
//
//        $this->_collection
//            ->addStoreFilter(Mage::app()->getStore()->getId())
//            ->addCustomerFilter(Mage::getSingleton('customer/session')->getCustomerId())
//            ->setDateOrder()
//            ->setPageSize(5)
//            ->load()
//            ->addReviewSummary();
    }
}