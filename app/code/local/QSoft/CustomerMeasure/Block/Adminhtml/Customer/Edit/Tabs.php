<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/3/2016
 * Time: 3:59 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Customer_Edit_Tabs extends Mage_Adminhtml_Block_Customer_Edit_Tabs
{
    protected function _beforeToHtml()
    {

        $this->addTab('customer_measure', array(
            'label' => Mage::helper('qsoft_customermeasure')->__('Customer Measurement'),
            'class' => 'ajax',
            'url' => $this->getUrl('admin_customer_measure/adminhtml_customer/customerMeasure', array('_current' => true)),
        ));

        $this->_updateActiveTab();
        Varien_Profiler::stop('customer/tabs');
        return parent::_beforeToHtml();
    }
}