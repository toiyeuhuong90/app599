<?php
require_once('Mage/Customer/controllers/AccountController.php');


class QSoft_Customer_AccountController extends Mage_Customer_AccountController{
    /**
     * Change customer password action
     */
    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getSession()->getCustomer();

            /** @var $customerForm Mage_Customer_Model_Form */
            $customerForm = $this->_getModel('customer/form');
            $customerForm->setFormCode('customer_account_edit')
                ->setEntity($customer);



            $errors = array();
            if ($this->getRequest()->getParam('change_password')) {
                $currPass   = $this->getRequest()->getPost('current_password');
                $newPass    = $this->getRequest()->getPost('password');
                $confPass   = $this->getRequest()->getPost('confirmation');

                $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
                if ( $this->_getHelper('core/string')->strpos($oldPass, ':')) {
                    list($_salt, $salt) = explode(':', $oldPass);
                } else {
                    $salt = false;
                }

                if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                    if (strlen($newPass)) {
                        /**
                         * Set entered password and its confirmation - they
                         * will be validated later to match each other and be of right length
                         */
                        $customer->setPassword($newPass);
                        $customer->setPasswordConfirmation($confPass);
                    } else {
                        $errors[] = $this->__('New password field cannot be empty.');
                    }
                } else {
                    $errors[] = $this->__('Invalid current password');
                }
            } elseif($this->getRequest()->getParam('save_address')){
                if($addresss = $this->getRequest()->getPost('address')){
                    foreach($addresss as $id=>$address){
                        $this->saveAddress($id, $address);
                    }
                    $this->_getSession()->addSuccess($this->__('The address has been saved.'));
                }
            }else {
                $customerData = $customerForm->extractData($this->getRequest());
                $customerErrors = $customerForm->validateData($customerData);
                 if ($customerErrors !== true) {
                     $errors = array_merge($customerErrors, $errors);
                 }
                $customerForm->compactData($customerData);
                $errors = array();

                // If password change was requested then add it to common validation scheme


                // Validate account and compose list of errors if any
                $customerErrors = $customer->validate();
                if (is_array($customerErrors)) {
                    $errors = array_merge($errors, $customerErrors);
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }

            try {
                $customer->cleanPasswordsValidationData();


                $customer->save();
                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('The account information has been saved.'));

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
            }
        }

        $this->_redirect('*/*/edit');
    }

    /*
     * update customer address
     * */

    protected function saveAddress($addressId, $data){
        $customer = $this->_getSession()->getCustomer();
        /* @var $address Mage_Customer_Model_Address */
        $address  = Mage::getModel('customer/address');
        $existsAddress = $customer->getAddressById($addressId);
        if ($existsAddress->getId() && $existsAddress->getCustomerId() == $customer->getId()) {
            $address->setId($existsAddress->getId());
        }

        $errors = array();

        /* @var $addressForm Mage_Customer_Model_Form */
        $addressForm = Mage::getModel('customer/form');
        $addressForm->setFormCode('customer_address_edit')
            ->setEntity($address);

        try {
            $addressForm->compactData($data);
            $address->setCustomerId($customer->getId())
                ->setIsDefaultBilling($data['default_billing'])
                ->setIsDefaultShipping($data['default_shipping']);

            $addressErrors = $address->validate();
            if ($addressErrors !== true) {
                $errors = array_merge($errors, $addressErrors);
            }

            if (count($errors) === 0) {
                $address->save();

                return;
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->setAddressFormData($this->getRequest()->getPost())
                ->addException($e, $e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->setAddressFormData($this->getRequest()->getPost())
                ->addException($e, $this->__('Cannot save address.'));
        }
    }
}