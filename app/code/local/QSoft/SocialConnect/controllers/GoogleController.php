<?php
class QSoft_SocialConnect_GoogleController extends QSoft_SocialConnect_Controller_Abstract
{

    protected function _disconnectCallback(Mage_Customer_Model_Customer $customer) {
        Mage::helper('qsoft_socialconnect/google')->disconnect($customer);

        Mage::getSingleton('core/session')
            ->addSuccess(
                $this->__('You have successfully disconnected your Google account from our store account.')
            );
    }

    protected function _connectCallback() {
        $errorCode = $this->getRequest()->getParam('error');
        $code = $this->getRequest()->getParam('code');
        $state = $this->getRequest()->getParam('state');
        if(!($errorCode || $code) && !$state) {
            // Direct route access - deny
            return $this;
        }

        if(!$state || $state != Mage::getSingleton('core/session')->getGoogleCsrf()) {
            return $this;
        }

        if($errorCode) {
            // Google API red light - abort
            if($errorCode === 'access_denied') {
                Mage::getSingleton('core/session')
                    ->addNotice(
                        $this->__('Google Connect process aborted.')
                    );

                return $this;
            }

            throw new Exception(
                sprintf(
                    $this->__('Sorry, "%s" error occured. Please try again.'),
                    $errorCode
                )
            );
        }

        if ($code) {
            // Google API green light - proceed

            $info = Mage::getModel('qsoft_socialconnect/google_info')->load();
            /* @var $info QSoft_SocialConnect_Model_Google_Info */

            $token = $info->getClient()->getAccessToken();

            $customersByGoogleId = Mage::helper('qsoft_socialconnect/google')
                ->getCustomersByGoogleId($info->getId());

            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                // Logged in user
                if($customersByGoogleId->getSize()) {
                    // Google account already connected to other account - deny
                    Mage::getSingleton('core/session')
                        ->addNotice(
                            $this->__('Your Google account is already connected to one of our store accounts.')
                        );

                    return $this;
                }

                // Connect from account dashboard - attach
                $customer = Mage::getSingleton('customer/session')->getCustomer();

                Mage::helper('qsoft_socialconnect/google')->connectByGoogleId(
                    $customer,
                    $info->getId(),
                    $token
                );

                Mage::getSingleton('core/session')->addSuccess(
                    $this->__('Your Google account is now connected to your store account. You can now login using our Google Login button or using store account credentials you will receive to your email address.')
                );

                return $this;
            }

            if($customersByGoogleId->getSize()) {
                // Existing connected user - login
                $customer = $customersByGoogleId->getFirstItem();

                Mage::helper('qsoft_socialconnect/google')->loginByCustomer($customer);

                Mage::getSingleton('core/session')
                    ->addSuccess(
                        $this->__('You have successfully logged in using your Google account.')
                    );

                return $this;
            }

            $customersByEmail = Mage::helper('qsoft_socialconnect/google')
                ->getCustomersByEmail($info->getEmail());

            if($customersByEmail->getSize())  {
                // Email account already exists - attach, login
                $customer = $customersByEmail->getFirstItem();

                Mage::helper('qsoft_socialconnect/google')->connectByGoogleId(
                    $customer,
                    $info->getId(),
                    $token
                );

                Mage::getSingleton('core/session')->addSuccess(
                    $this->__('We have discovered you already have an account at our store. Your Google account is now connected to your store account.')
                );

                return $this;
            }

            // New connection - create, attach, login
            $givenName = $info->getGivenName();
            if(empty($givenName)) {
                throw new Exception(
                    $this->__('Sorry, could not retrieve your Google first name. Please try again.')
                );
            }

            $familyName = $info->getFamilyName();
            if(empty($familyName)) {
                throw new Exception(
                    $this->__('Sorry, could not retrieve your Google last name. Please try again.')
                );
            }

            Mage::helper('qsoft_socialconnect/google')->connectByCreatingAccount(
                $info->getEmail(),
                $info->getGivenName(),
                $info->getFamilyName(),
                $info->getId(),
                $token
            );

            Mage::getSingleton('core/session')->addSuccess(
                $this->__('Your Google account is now connected to your new user account at our store. Now you can login using our Google Login button.')
            );
        }
    }

}
