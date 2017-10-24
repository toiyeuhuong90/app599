<?php
/**
 *Storelocator Extension
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://store.monyet.com/license.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to admin@monyet.com so we can mail you a copy immediately.
 *
 * @category   Magento Extensions
 * @package    Monyet_Storelocator
 * @author     Monyet <sales@monyet.com>
 * @copyright  2007-2011 Monyet
 * @license    http://store.monyet.com/license.txt
 * @version    1.0.1
 * @link       http://store.monyet.com
 */
 
class Monyet_Storelocator_Model_Observer
{
	
	public function saveStoreAddress($order)
	{	
		$order_id = $order->getId();
		$shippingMethod = $order->getShippingMethod();
		$shippingMethod = explode('_',$shippingMethod);
		$shippingCode = $shippingMethod[0];
		if($shippingCode != "storelocator")
			return $this;
	
		$storelocator = Mage::getSingleton('checkout/session')->getData('storelocator_session');
		if(isset($storelocator['is_storelocator']) && $storelocator['is_storelocator'] == '1')
		{
			try {
				$store_id = isset($storelocator['store_id']) ? $storelocator['store_id'] : null;
				if(!$store_id)
					return $this;
				$shippingdesct = $order->getShippingDescription();
				$store = Mage::getModel('storelocator/store')->load($store_id);
				
				if ($store) {
					$latitude = $store->getStoreLatitude();
					$longitude = $store->getStoreLongitude();
					if($latitude !=0 && $longitude !=0) {
						$shippingdesct .='<br/><img src=http://maps.google.com/maps/api/staticmap?center='.$latitude.','.$longitude.'&zoom=14&size=200x200&markers=color:red|label:S|'.$latitude.','.$longitude.'&sensor=false /><br/>';
					}	
				}
				
				$order->setShippingDescription($shippingdesct)
						->save();
				
				
			} catch (Exception $e) {
			
			}					
		}
	}

	public function sendEmail($order)
	{
		if(Mage::getStoreConfig('general/storelocator/emailtostoreowner')) {
			Mage::helper('storelocator/email')->sendNoticeEmailToStoreOwner($order);
		}
		if(Mage::getStoreConfig('general/storelocator/emailtoadmin')) {
			Mage::helper('storelocator/email')->sendNoticeEmailToAdmin($order);
		}
		Mage::getSingleton('checkout/session')->unsetData('storelocator_session');
		
		return $this;
	}	
		
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }
}