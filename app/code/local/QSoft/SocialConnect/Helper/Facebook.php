<?php
/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 10/27/2015
 * Time: 5:21 PM
 */

class QSoft_SocialConnect_Helper_Facebook extends Mage_Core_Helper_Abstract
{

    public function disconnect(Mage_Customer_Model_Customer $customer)
    {
        $client = Mage::getSingleton('qsoft_socialconnect/facebook_oauth2_client');

        // TODO: Move into QSoft_SocialConnect_Model_Facebook_Info_User
        try {
            $client->setAccessToken(unserialize($customer->getQSoftSocialconnectFtoken()));
            $client->api('/me/permissions', 'DELETE');
        } catch (Exception $e) {
        }

        $pictureFilename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
            . DS
            . 'qsoft'
            . DS
            . 'socialconnect'
            . DS
            . 'facebook'
            . DS
            . $customer->getQSoftSocialconnectFid();

        if (file_exists($pictureFilename)) {
            @unlink($pictureFilename);
        }

        $customer->setQSoftSocialconnectFid(null)
            ->setQSoftSocialconnectFtoken(null)
            ->save();
    }

    public function connectByFacebookId(
        Mage_Customer_Model_Customer $customer,
        $facebookId,
        $token)
    {
        $customer->setQSoftSocialconnectFid($facebookId)
            ->setQSoftSocialconnectFtoken(serialize($token))
            ->save();

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function connectByCreatingAccount(
        $email,
        $firstName,
        $lastName,
        $facebookId,
        $birthday = null,
        $gender = null,
        $token)
    {
        $customer = Mage::getModel('customer/customer');

        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
            ->setEmail($email)
            ->setFirstname($firstName)
            ->setLastname($lastName)
            ->setQSoftSocialconnectFid($facebookId)
            ->setQSoftSocialconnectFtoken(serialize($token))
            ->setPassword($customer->generatePassword(10));

        if (!empty($birthday)) {
            $customer->setDob($birthday);
        }

        if (!empty($gender)) {
            $customer->setGender($gender);
        }

        $customer->setConfirmation(null);
        $customer->save();

        $customer->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

    }

    public function loginByCustomer(Mage_Customer_Model_Customer $customer)
    {
        if ($customer->getConfirmation()) {
            $customer->setConfirmation(null);
            $customer->save();
        }

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function getCustomersByFacebookId($facebookId)
    {
        $customer = Mage::getModel('customer/customer');

        $collection = $customer->getCollection()
            ->addAttributeToFilter('qsoft_socialconnect_fid', $facebookId)
            ->setPageSize(1);

        if ($customer->getSharingConfig()->isWebsiteScope()) {
            $collection->addAttributeToFilter(
                'website_id',
                Mage::app()->getWebsite()->getId()
            );
        }

        return $collection;
    }

    public function getCustomersByEmail($email)
    {
        $customer = Mage::getModel('customer/customer');

        $collection = $customer->getCollection()
            ->addFieldToFilter('email', $email)
            ->setPageSize(1);

        if ($customer->getSharingConfig()->isWebsiteScope()) {
            $collection->addAttributeToFilter(
                'website_id',
                Mage::app()->getWebsite()->getId()
            );
        }

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection->addFieldToFilter(
                'entity_id',
                array('neq' => Mage::getSingleton('customer/session')->getCustomerId())
            );
        }

        return $collection;
    }

    public function getProperDimensionsPictureUrl($facebookId, $pictureUrl)
    {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)
            . 'qsoft'
            . '/'
            . 'socialconnect'
            . '/'
            . 'facebook'
            . '/'
            . $facebookId;

        $filename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
            . DS
            . 'qsoft'
            . DS
            . 'socialconnect'
            . DS
            . 'facebook'
            . DS
            . $facebookId;

        $directory = dirname($filename);

        if (!file_exists($directory) || !is_dir($directory)) {
            if (!@mkdir($directory, 0777, true))
                return null;
        }

        if (!file_exists($filename) ||
            (file_exists($filename) && (time() - filemtime($filename) >= 3600))
        ) {
            $client = new Zend_Http_Client($pictureUrl);
            $client->setStream();
            $response = $client->request('GET');
            stream_copy_to_stream($response->getStream(), fopen($filename, 'w'));

            $imageObj = new Varien_Image($filename);
            $imageObj->constrainOnly(true);
            $imageObj->keepAspectRatio(true);
            $imageObj->keepFrame(false);
            $imageObj->resize(150, 150);
            $imageObj->save($filename);
        }

        return $url;
    }

}