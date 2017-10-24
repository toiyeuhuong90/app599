<?php
require_once('Mage/Customer/controllers/AccountController.php');


class QSoft_Customer_AjaxController extends Mage_Customer_AccountController{
    public function loginAction()
    {
        $result = array();
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    if($session->login($login['username'], $login['password'])){
                        $result['status'] = 1;
                        $customerInfo = Mage::helper('productdesign')->getCustomerMeasureValues();
                        $result['info'] = $customerInfo;
                        if($customerInfo['hasMeasure']==0){
                            $block = $this->getLayout()->createBlock('qsoft_customermeasure/account')->setTemplate('qsoft/productdesign/catalog/product/view/measure.phtml');
                            $result['measure'] = $block->toHtml();
                        }
                    }else{
                        $result['status'] = 0;

                    }
                } catch (Mage_Core_Exception $e) {

                            $message = $e->getMessage();

                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }


    /**
     * Create customer account action
     */
    public function createAction()
    {
        $result = array();
        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();

        $customer = $this->_getCustomer();

        try {
            $errors = $this->_getCustomerErrors($customer);

            if (empty($errors)) {
                $customer->cleanPasswordsValidationData();
                $customer->save();
                $this->_dispatchRegisterSuccess($customer);
                if ($customer->isConfirmationRequired()) {
                    /** @var $app Mage_Core_Model_App */
                    $app = $this->_getApp();
                    /** @var $store  Mage_Core_Model_Store*/
                    $store = $app->getStore();
                    $customer->sendNewAccountEmail(
                        'confirmation',
                        $session->getBeforeAuthUrl(),
                        $store->getId()
                    );
                    $result['message'] =  $this->__('Account confirmation is required. Please, check your email for the confirmation link. ');
                }else{
                    $session->setCustomerAsLoggedIn($customer);
                    $result['status'] = 1;
                    $result['message'] =  $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName());
                    $customerInfo = Mage::helper('productdesign')->getCustomerMeasureValues();
                    $result['info'] = $customerInfo;
                    $block = $this->getLayout()->createBlock('qsoft_customermeasure/account')->setTemplate('qsoft/productdesign/catalog/product/view/measure.phtml');
                    $result['measure'] = $block->toHtml();
                }


            } else {
                $result['message'] = implode("\n", $this->_addSessionError($errors));
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('customer/account/forgotpassword');
                $result['message'] =  $this->__('There is already an account with this email address. Please try again with another email.');
            } else {
                $result['message'] =  $this->_escapeHtml($e->getMessage());
            }
        } catch (Exception $e) {
            $result['message'] =  $this->__('Cannot save the customer.');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}