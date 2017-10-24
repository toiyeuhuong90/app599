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
 
class Monyet_Storelocator_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getStoresUrl()
	{
		return $this->_getUrl('storelocator/index/',array());
	}
	
	public function getTablePrefix()
	{
		$table = Mage::getResourceSingleton("eav/entity_attribute")->getTable("eav/attribute");
		
		$prefix = str_replace("eav_attribute","",$table);
		
		return $prefix;
	}
	
	public function getListStoreByCustomerAddress()
	{
		$options = array();

		$cart = Mage::getSingleton('checkout/cart');
		$shippingAddress = $cart->getQuote()->getShippingAddress();

		$collection = Mage::getResourceModel('storelocator/store_collection')
						->addFieldToFilter('country',$shippingAddress->getCountryId())	
					;			
		
		if($shippingAddress->getPostcode())
		{
			$collection->addFieldToFilter('zipcode',$shippingAddress->getPostcode());			
		}		
				
		
		if(is_array($shippingAddress->getStreet()))
		{
			$street = $shippingAddress->getStreet();
			$suburb = trim(substr($street[0],strrpos($street[0],',')+1));	
			$collection->addFieldToFilter('suburb',$suburb);			
		} else if($shippingAddress->getCity()){
			$collection->addFieldToFilter('city',$shippingAddress->getCity());
		} else if($shippingAddress->getRegion()){
			$collection->addFieldToFilter('state',$shippingAddress->getRegion());		
		}
		
		
		if(count($collection))
		foreach($collection as $store)
		{
			$options[$store->getId()] = $store->getStoreName();
		}		
		return $options;
	}

	public function getUpdateStoreUrl()
	{
		return $this->_getUrl('storelocator/index/updatestore',array('_secure'=>true));		
	}	
	
	public function getFinalSku($sku)
	{
		try{
			$sku = Mage::helper('core/string')->splitInjection($sku);
			return $sku;
		} catch(Exception $e){
			return $sku;
		}
	}

	public function getCustomerAddress()
	{
		$cSession = Mage::getSingleton('customer/session');

		$attribute = Mage::getModel("eav/entity_attribute")->load("customer_shipping_address_id","attribute_code");
					
		if($cSession->isLoggedIn() && $attribute->getId())
		{
			$address = Mage::helper('accountfield')
						->getShippingAddressByCustomerId($cSession->getCustomer()->getId());			
			if($address)
				return $address;
		}
		
		$cart = Mage::getSingleton('checkout/cart');
		return $cart->getQuote()->getShippingAddress();
	}
	public function getListCountry()
	{
		$listCountry = array();
		
		$collection = Mage::getResourceModel('directory/country_collection')
						->loadByStore();
		
		if(count($collection))
		{
			foreach($collection as $item)
			{
				$listCountry[$item->getId()] = $item->getName();
			}
		}
		
		return $listCountry;
	}
	
	public function getOptionCountry()
	{
		$optionCountry = array();
		
		$collection = Mage::getResourceModel('directory/country_collection')
								->loadByStore();
		
		if(count($collection))
		{
			foreach($collection as $item)
			{
				$optionCountry[] = array('value' => $item->getId(), 'label' => $item->getName(), );
			}
		}
		
		return $optionCountry;
	}	
	
	
	public function getOptionLocation()
	{
		$options = array(array('value'=>0, 'label'=>$this->__('None')));
		
		$list = $this->getListLocation();
		
		if(count($list))
		{
			foreach($list as $value=>$label)
			{
				$options[] = array('value'=>$value, 'label'=>$label);
			}
		}
		
		return $options;
	}
		
	public function getLimitedConfigStore($_stores,$num)
	{
		$tops = array();
		while(count($_stores) && $num)
		{
			$store = $this->_getLimited($_stores);
			if($store->getId())
			{	
				unset($_stores[$store->getData('index')]);
				$tops[] = $store;
				$num--;
			}
		}
		
		return $tops;
	}
	
	protected function _getLimited($_stores)
	{
		$object = new Varien_Object();
		$object->setData('distance',9999999999);
		foreach($_stores as $index=>$_store)
		{
			if($_store->getData('distance') < $object->getData('distance'))
			{
				$object = $_store;
				$object->setData('index',$index);
			}
		}
		
		return $object;
		
	}
	
	public function getSearchFields()
	{
		$storeId = Mage::app()->getStore()->getId();
		$searchconfig = explode(',', Mage::getStoreConfig("general/storelocator/search_fields", $storeId));
		return $searchconfig;
	}
	
	public function getResponseBody($url)
	{
		if(ini_get('allow_url_fopen') != 1) 
		{
			@ini_set('allow_url_fopen', '1');
		}
		
		if(ini_get('allow_url_fopen') != 1) 
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			$contents = curl_exec($ch);
			curl_close($ch);
		} else {
			$contents = file_get_contents($url);
		}

		return $contents;
	}
}