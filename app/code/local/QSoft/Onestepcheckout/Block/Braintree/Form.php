<?php
$file = Mage::getBaseDir('code').'/local/Braintree/Payments/Block/Form.php';
if(file_exists($file)){
	if(!class_exists('Braintree_Payments_Block_Form', false))
		include_once($file);
	class QSoft_Onestepcheckout_Block_Braintree_Form extends Braintree_Payments_Block_Form{
	}
}
else{
	class QSoft_Onestepcheckout_Block_Braintree_Form extends Mage_Core_Block_Template{
	}
}