<?php

/**
 * NOTICE OF LICENSE
 *
 * You may not give, sell, distribute, sub-license, rent, lease or lend
 * any portion of the Software or Documentation to anyone.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   ET
 * @package
 * @copyright  Copyright (c) 2015 ET Web Solutions (http://etwebsolutions.com)
 * @contacts   support@etwebsolutions.com
 * @license    http://shop.etwebsolutions.com/etws-license-commercial-v1/   ETWS Commercial License (ECL1)
 */
class ET_SocialLogin_Block_Adminhtml_System_Config_Form_Field_Callback
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $elementData = $element->getData('original_data');
        $provider = $elementData['provider'];
        $storeUrlEnabled = Mage::getStoreConfig('web/url/use_store');
        $url = '';

        if ($storeUrlEnabled) {
            $stores = Mage::app()->getStores();

            foreach ($stores as $store) {
                $url .= $store->getBaseUrl() . 'social/auth/callback/provider/' . $provider . '/<br><br>';
            }
        } else {
            $baseUrl = Mage::getBaseUrl( Mage_Core_Model_Store::URL_TYPE_WEB, true );
            $url = $baseUrl . 'social/auth/callback/provider/' . $provider . '/<br>';
        }

        return $url;
    }
}