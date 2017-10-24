<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/14/2016
 * Time: 4:41 PM
 */
class QSoft_CustomerMeasure_Adminhtml_CustomerController extends Mage_Adminhtml_Controller_Action
{
    protected function _initCustomer($idFieldName = 'id')
    {
        $this->_title($this->__('Customers'))->_title($this->__('Manage Customers'));

        $customerId = (int)$this->getRequest()->getParam($idFieldName);
        $customer = Mage::getModel('customer/customer');

        if ($customerId) {
            $customer->load($customerId);
            
        }

        Mage::register('current_customer', $customer);

        return $this;
    }

    public function customerMeasureAction()
    {
        $this->_initCustomer();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('qsoft_customermeasure/adminhtml_customer_edit_tab_measure', 'admin.customer.measure.profiles')->setCustomerId(Mage::registry('current_customer')->getId())
                ->setUseAjax(true)
                ->toHtml()
        );
    }
}