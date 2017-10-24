<?php
class QSoft_Onestepcheckout_Block_Paypal_login extends Mage_Core_Block_Template{

 
    protected function _toHtml(){
        $isExtensionEnabled = Mage::getStoreConfigFlag('onestepcheckout/paypallogin/status');
        if ($isExtensionEnabled) {
            return parent::_toHtml();
        }
        return '';
    }
	
	public function getPayPalButtonUrl(){
		return Mage::helper('onestepcheckout/paypal')->getPayPalButtonUrl();
	}

}
