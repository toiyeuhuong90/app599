<?php

class Monyet_Storelocator_Block_Adminhtml_Store_Edit_Tab_Gmap extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareLayout()
	{
		$this->setTemplate('monyet/storelocator/gmap.phtml');
	}
	
	public function getStore()
	{
		if(!$this->hasData('store_data'))
		{
			if(Mage::registry('store_data')) {
				$this->setData('store_data',Mage::registry('store_data'));
			} else {	
				$store = Mage::getModel('storelocator/store')->load($this->getRequest()->getParam('id'));
				$this->setData('store_data',$store);
			}			
		}
		
		return $this->getData('store_data');
	}
	
	public function getCoordinates()
	{
		$store = $this->getStore();
		$address['street'] = $store->getSuburb();
		$address['street'] = '';
		$address['city'] = $store->getCity();
		$address['region'] = $store->getRegion();
		$address['zipcode'] = $store->getZipcode();
		$address['country'] = $store->getCountryName();
		
		$coordinates = Mage::getModel('storelocator/gmap')
							->getCoordinates($address);
		if(! $coordinates)
		{	
			$coordinates['lat'] = '0.000';
			$coordinates['lng'] = '0.000';			
		}

		return $coordinates;
	}
	
	public function getGmapKey()
	{
		if(!$this->hasData('gmap_key'))
		{
			$this->setData('gmap_key',Mage::getModel('storelocator/gmap')->getGmapKey());
		}
		
		return $this->getData('gmap_key');	
	}
	
}