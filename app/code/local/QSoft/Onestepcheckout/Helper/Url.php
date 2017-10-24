<?php
class QSoft_Onestepcheckout_Helper_Url extends Mage_Checkout_Helper_Url{

    /**
     * Retrieve checkout url
     *
     * @return string
     */
    public function getCheckoutUrl(){
    	if (Mage::helper('onestepcheckout')->isEnable()){
        	return $this->_getUrl('onestepcheckout', array('_secure'=>true));
    	}else{
    		return parent::getCheckoutUrl();
    	}
    }

  


    /**
     * One Page (OP) checkout urls
     */
    public function getOPCheckoutUrl(){
    	if (Mage::helper('onestepcheckout')->isEnable()){
        	return $this->_getUrl('onestepcheckout', array('_secure'=>true));
    	}else{
    		return parent::getOPCheckoutUrl();
    	}
    }
}
