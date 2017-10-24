<?php

class TTS_Onepay_Model_Onepayquocte extends Mage_Payment_Model_Method_Abstract
{
   protected $_code  = 'onepayquocte';
   protected $_formBlockType = 'onepay/form';
   protected $_infoBlockType = 'onepay/info';
 public function getTitle()
    {
        return $this->getConfigData('title');
    }
   public function get_icon()
    {
        return $this->getConfigData('icon');
    }

  public function getOrderPlaceRedirectUrl()
		{			
				return Mage::getUrl('onepay/standard/redirect', array('_secure' => true,'onepay'=> '1'));
		}
	public function getUrlOnepay($orderid){
		
		   $_order = Mage::getModel('sales/order')->loadByIncrementId($orderid);
		   $getGrandTotal = $_order->getGrandTotal();
		   $getGrandTotalArr = explode(".", $getGrandTotal);
		   $getGrandTotalArr0 = $getGrandTotalArr[0];
		   $getGrandTotalArr1 = $getGrandTotalArr[1];
		   $getGrandTotalArr1 = substr ($getGrandTotalArr1 , 0 ,2 );
		   $amount_total = $getGrandTotalArr0.'.'.$getGrandTotalArr1;
		   
		$arrayvalue =array();
					$arrayvalue['Title'] = "VPC 3-Party";
					$arrayvalue['AgainLink'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
					$arrayvalue['vpc_Merchant']=$this->getConfigData('vpc_Merchant');
					$arrayvalue['vpc_AccessCode']=$this->getConfigData('vpc_AccessCode');
					$arrayvalue['vpc_MerchTxnRef'] = date( 'YmdHis' ).rand();
					$arrayvalue['vpc_OrderInfo']= $orderid;
					$arrayvalue['vpc_Amount']= ($amount_total*100);
					$arrayvalue['vpc_ReturnURL']=Mage::getUrl('onepay/standard/successquocte');
					$arrayvalue['vpc_Version']=$this->getConfigData('vpc_Version');
					$arrayvalue['vpc_Command']=$this->getConfigData('vpc_Command');
					//$arrayvalue['vpc_Currency']=$this->getConfigData('vpc_Currency');
					$arrayvalue['vpc_Locale']=$this->getConfigData('vpc_Locale');
				    $arrayvalue['vpc_TicketNo']=$_SERVER ['REMOTE_ADDR'];
	
					//$arrayvalue['vpc_SHIP_Street01']= $_order->getShippingAddress()->getStreet1(); //
					//$arrayvalue['vpc_SHIP_Provice']=$_order->getShippingAddress()->getCity(); 
					//$arrayvalue['vpc_SHIP_City']=$_order->getShippingAddress()->getRegion(); 
					//$arrayvalue['vpc_SHIP_Country']=$_order->getShippingAddress()->getCountry_id(); 
					//$arrayvalue['vpc_Customer_Phone']=$_order->getShippingAddress()->getTelephone();//
					//$arrayvalue['vpc_Customer_Email']=$_order->getCustomerEmail();
					
					//$arrayvalue['AVS_Street01']= "customer billing address"; //
					//$arrayvalue['AVS_City']= "customer billing city"; 
					//$arrayvalue['AVS_PostCode']= "customer billing post code";
					//$arrayvalue['AVS_Country']= "customer billing country"; 
				
				$SECURE_SECRET = $this->getConfigData('hash_code');
				
				$vpcURL = $this->getConfigData('virtualPaymentClientURL')."?";
				
				
				$stringHashData = "";
				
				ksort ($arrayvalue);
				
				
				$appendAmp = 0;
				
				foreach($arrayvalue as $key => $value) {
				
				  
				    if (strlen($value) > 0) {
				      
				        if ($appendAmp == 0) {
				            $vpcURL .= urlencode($key) . '=' . urlencode($value);
				            $appendAmp = 1;
				        } else {
				            $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
				        }
				     
				        if ((strlen($value) > 0) && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
						    $stringHashData .= $key . "=" . $value . "&";
						}
				    }
				}
				
				$stringHashData = rtrim($stringHashData, "&");
				
				if (strlen($SECURE_SECRET) > 0) {
				
				    $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*',$SECURE_SECRET)));
				}
		return 	$vpcURL;		
	}	
	public function getResponseDescription($responseCode) {
	
		  switch ($responseCode) {
        case "0" :
            $result = "Transaction Successful";
            break;
        case "?" :
            $result = "Transaction status is unknown";
            break;
        case "1" :
            $result = "Bank system reject";
            break;
        case "2" :
            $result = "Bank Declined Transaction";
            break;
        case "3" :
            $result = "No Reply from Bank";
            break;
        case "4" :
            $result = "Expired Card";
            break;
        case "5" :
            $result = "Insufficient funds";
            break;
        case "6" :
            $result = "Error Communicating with Bank";
            break;
        case "7" :
            $result = "Payment Server System Error";
            break;
        case "8" :
            $result = "Transaction Type Not Supported";
            break;
        case "9" :
            $result = "Bank declined transaction (Do not contact Bank)";
        case "99" :
            $result = "User Cancel";
            break;
        case "A" :
            $result = "Transaction Aborted";
            break;
        case "C" :
            $result = "Transaction Cancelled";
            break;
        case "D" :
            $result = "Deferred transaction has been received and is awaiting processing";
            break;
        case "F" :
            $result = "3D Secure Authentication failed";
            break;
        case "I" :
            $result = "Card Security Code verification failed";
            break;
        case "L" :
            $result = "Shopping Transaction Locked (Please try the transaction again later)";
            break;
        case "N" :
            $result = "Cardholder is not enrolled in Authentication scheme";
            break;
        case "P" :
            $result = "Transaction has been received by the Payment Adaptor and is being processed";
            break;
        case "R" :
            $result = "Transaction was not processed - Reached limit of retry attempts allowed";
            break;
        case "S" :
            $result = "Duplicate SessionID (OrderInfo)";
            break;
        case "T" :
            $result = "Address Verification Failed";
            break;
        case "U" :
            $result = "Card Security Code Failed";
            break;
        case "V" :
            $result = "Address Verification and Card Security Code Failed";
            break;
		case "B" :
            $result = "Fraud Risk Block,3D Secure Authentication failed";
            break;
		case "Z" :
            $result = "Transaction was block by OFD";
            break;
        default  :
            $result = "Unable to be determined";//Fraud Risk Block 
    }
   
		return $result;
}
	public function transStatus($hashValidated,$txnResponseCode){
	$transStatus = "";
	if($hashValidated=="CORRECT" && $txnResponseCode=="0"){
		$transStatus = "Transaction Success";
	}elseif ($txnResponseCode!="0"){
		$transStatus = "Transaction Fail </br>".$this->getResponseDescription($txnResponseCode);
	}elseif ($hashValidated=="INVALID HASH"){
		$transStatus = "Transaction Pendding";
	}
	return $transStatus;
	}
}