<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 11/17/2015
 * Time: 10:10 AM
 */
class QSoft_SocialConnect_VkController extends QSoft_SocialConnect_Controller_Abstract
{
    protected function _disconnectCallback(Mage_Customer_Model_Customer $customer)
    {
        Mage::helper('qsoft_socialconnect/vk')->disconnect($customer);

        Mage::getSingleton('core/session')
            ->addSuccess(
                $this->__('You have successfully disconnected your Vk account from our store account.')
            );
    }

    protected function _connectCallback()
    {
        $errorCode = $this->getRequest()->getParam('error');
        $code = $this->getRequest()->getParam('code');
        $state = $this->getRequest()->getParam('state');

        if (!($errorCode || $code) && !$state) {
            // Direct route access - deny
            return $this;
        }

        if ($errorCode) {
            // Vk API read light - abort
            if ($errorCode === 'access_denied') {
                Mage::getSingleton('core/session')
                    ->addNotice(
                        $this->__('Vk Connection process aborted.')
                    );

                return $this;
            }

            throw new Exception(
                sprintf(
                    $this->__('Sorry, "%s" error occurred. Please try again.'),
                    $errorCode
                )
            );
        }

        if ($code) {
            /** @var QSoft_SocialConnect_Helper_Vk $helper */
            $helper = Mage::helper('qsoft_socialconnect/vk');

            // Vk API green light - proceed
            /** @var QSoft_SocialConnect_Model_Vk_Info $info */
            $info = Mage::getModel('qsoft_socialconnect/vk_info');

            $token = $info->getClient()->getAccessToken($code);


            $info->load();

            $customersByVkId = $helper->getCustomersByVkId($info->uid);

            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                // Logged in user
                if ($customersByVkId->getSize()) {
                    // Vk account already connected to other account - deny
                    Mage::getSingleton('core/session')
                        ->addNotice(
                            $this->__('Your Vk account is already connected to one of our store accounts.')
                        );

                    return $this;
                }

                // Connect from account dashboard - attach
                $customer = Mage::getSingleton('customer/session')->getCustomer();

                $helper->connectByVkId(
                    $customer,
                    $info->uid,
                    $token
                );

                Mage::getSingleton('core/session')->addSuccess(
                    $this->__('Your Vk account is now connected to your store account. You can now login using ' .
                        'our Vk Login button or using store account credentials you will receive to your email ' .
                        'address.')
                );

                return $this;
            }

            if ($customersByVkId->getSize()) {
                // Existing connected user - login
                $customer = $customersByVkId->getFirstItem();

                $helper->loginByCustomer($customer);

                Mage::getSingleton('core/session')
                    ->addSuccess(
                        $this->__('You have successfully logged in using your Vk account.')
                    );

                return $this;
            }

            $customersByEmail = $helper->getCustomersByEmail($token->email);

            if ($customersByEmail->getSize()) {
                // Email account already exists - attach, login
                $customer = $customersByEmail->getFirstItem();

                $helper->connectByVkId(
                    $customer,
                    $info->uid,
                    $token
                );

                Mage::getSingleton('core/session')->addSuccess(
                    $this->__('We have discovered you already have an account at our store. Your Vk account is ' .
                        'now connected to your store account.')
                );

                return $this;
            }

            // New connection - create, attach, login
            $firstName = $info->first_name;
            if (empty($firstName)) {
                throw new Exception(
                    $this->__('Sorry, could not retrieve your Vk first name. Please try again.')
                );
            }

            $lastName = $info->last_name;
            if (empty($lastName)) {
                throw new Exception(
                    $this->__('Sorry, could not retrieve your Vk last name. Please try again.')
                );
            }

            $birthday = $info->bdate;
            $birthday = Mage::app()->getLocale()->date($birthday, null, null, false)
                ->toString('yyyy-MM-dd');

            $gender = $info->sex;
            if (empty($gender)) {
                $gender = null;
            } else if ($gender = 'male') {
                $gender = 1;
            } else if ($gender = 'female') {
                $gender = 2;
            }

            $helper->connectByCreatingAccount(
                $token->email,
                $info->first_name,
                $info->last_name,
                $info->uid,
                $birthday,
                $gender,
                $token
            );
            Mage::getSingleton('core/session')->addSuccess(
                $this->__('Your Vk account is now connected to your new user account at our store.' .
                    ' Now you can login using our Vk Login button.')
            );
        }
    }
}