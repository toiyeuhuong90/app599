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
 
class Monyet_Storelocator_Model_Store extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('storelocator/store');
    }
	
	public function getFormatedAddress()
	{
		$address = $this->getAddress();
		
		return $address.', '. $this->getCity() .', '. $this->getRegion() .', '. $this->getZipcode() 
				. ', '. $this->getCountryName();
	}
	
	public function getFormatedAddressforMap()
	{
		$address = $this->getAddress();
		
		return $address.', <br>'. $this->getCity() .', '. $this->getRegion() .', '. $this->getZipcode() 
				. ', <br>'. $this->getCountryName();
	}
	
	public function getCountryName()
	{
		if($this->getCountry())
		if(! $this->hasData('country_name'))
		{
			$country = Mage::getModel('directory/country')
								->loadByCode($this->getCountry());
			$this->setData('country_name',$country->getName());
		}
		
		return $this->getData('country_name');
	}	
	
	public function getRegion()
	{
		if(! $this->getData('region'))
		{
			$this->setData('region',$this->getState());
		}
		
		return $this->getData('region');
	}
	
	public function getCity()
	{
		if(! $this->getData('city'))
		{
			$this->setData('city',$this->getCity());
		}
		
		return $this->getData('city');
	}
	
	public function getSuburb()
	{
		if(!$this->getData('suburb'))
		{
			$this->setData('suburb',$this->getName());
		}
		
		return $this->getData('suburb');
	}

	public function save()
	{
		if(!$this->getStoreLatitude() || ! $this->getStoreLongitude())
		{
			$address['street'] = $this->getAddress();
			$address['city'] = $this->getCity();
			$address['region'] = $this->getRegion();
			$address['zipcode'] = $this->getZipcode();
			$address['country'] = $this->getCountryName();
			
			$coordinates = Mage::getModel('storelocator/gmap')
								->getCoordinates($address);
			if($coordinates)
			{
				$this->setStoreLatitude($coordinates['lat']);
				$this->setStoreLongitude($coordinates['lng']);
			}else{
				$this->setStoreLatitude('0.000');
				$this->setStoreLongitude('0.000');			
			}
		}
		
		return parent::save();
	}
	
	public function getListStoreByCustomerAddress()
	{
		$options = array();

		$cart = Mage::getSingleton('checkout/cart');
		$shippingAddress = Mage::helper('storelocator')->getCustomerAddress();

		$collection = $this->getCollection()
						  ->addStoreFilter(Mage::app()->getStore())
						  ->addFieldToFilter('country',$shippingAddress->getCountryId());			
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
	
	public function getStoresUseGAPI()
	{
		$options = array();

		$cart = Mage::getSingleton('checkout/cart');
		$shippingAddress = Mage::helper('storelocator')->getCustomerAddress();

		$collection = $this->getCollection()
						->addFieldToFilter('country',$shippingAddress->getCountryId())	
					;			
		
		if($shippingAddress->getPostcode()){
			$collection->addFieldToFilter('zipcode',$shippingAddress->getPostcode());			
		}
		if($shippingAddress->getCity()){
			$collection->addFieldToFilter('city',$shippingAddress->getCity())					
					;
		}
		
		$stores = $this->filterStoresUseGAPI($collection);
		
		if(count($stores))
		foreach($stores as $store)
		{
			$options[$store->getId()] = $store->getStoreName() .' ('.number_format($store->getDistance()).' m)';
		}		
		return $options;
			
	}
	
	public function toListOptions()
	{
		$options = array();
		$stores = $this->getCollection()
						->addFieldToFilter('status', 1)
						->addStoreFilter(Mage::app()->getStore())
						->setOrder('store_name','ASC');
		if(count($stores)){
			foreach($stores as $store)
			{
				$options[$store->getId()]['label'] = $store->getStoreName();
				$options[$store->getId()]['info'] = $store;
			}	
		}
		return $options;		
	}	
	
	public function filterStoresUseGAPI()
	{
		$stores = array();
		$_storecollection = $this->getCollection()
								->addStoreFilter(Mage::app()->getStore())
								->addFieldToFilter('status',1);
		if(!count($_storecollection))
			return $stores;
		
		$shippingAddress = Mage::helper('storelocator')->getCustomerAddress();
		$oGmap = Mage::getModel('storelocator/gmap');
		
		$street = $shippingAddress->getStreet();
		if(strrpos($street[0],','))
			$address['street'] = trim(substr($street[0],0,strrpos($street[0],',')));				
		else
			$address['street'] = $street[0];
		
		$address['city'] = $shippingAddress->getCity();
		$address['region'] = $shippingAddress->getRegion();
		$address['zipcode'] = $shippingAddress->getPostcode();
		$address['country'] = $shippingAddress->getCountryId();	
		
		$coordinates = $oGmap->getCoordinates($address);
		
		if(!$coordinates){
			$address['street'] = trim(substr($street[0],strrpos($street[0],',')+1));
			$coordinates = $oGmap->getCoordinates($address);			
		}
	
		if(!$coordinates)
			return $this->toListOptions($_storecollection);
		
		$spoint['lat'] = $coordinates['lat'];
		$spoint['lng'] = $coordinates['lng'];
		
		foreach($_storecollection as $_store)
		{
			$dpoint['lat'] = $_store->getStoreLatitude();
			$dpoint['lng'] = $_store->getStoreLongitude();
			$distance = $oGmap->getDistance($spoint,$dpoint);
			$distance = $distance ? $distance : 999999999;
			$_store->setData('distance',$distance);
			$stores[] = $_store;
		}
		
		$storeID = Mage::app()->getStore()->getId();
		$top_n = Mage::getStoreConfig('general/storelocator/num_top_store',$storeID);
		$top_n = $top_n ? $top_n : 5;
		
		$stores = Mage::helper('storelocator')->getLimitedConfigStore($stores,$top_n);
		
		$options = array();
		
		if(count($stores))
		foreach($stores as $store)
		{
			$storeTitle = ($store->getDistance() && $store->getDistance()!= 999999999) ? $store->getStoreName() .' ('.number_format($store->getDistance()).' m)' : $store->getStoreName();
			$options[$store->getId()]['label'] = $storeTitle;
			$options[$store->getId()]['info'] = $store;
		}		

		
		return $options;		

	}
	
}