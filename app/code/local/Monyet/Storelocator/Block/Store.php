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
 
class Monyet_Storelocator_Block_Store extends Mage_Core_Block_Template
{

	public function __construct()
	{
		parent::__construct();
	}
		
	public function _prepareLayout()
    {
		parent::_prepareLayout();
		$pager = $this->getLayout()->createBlock('page/html_pager', 'storelocator.stores.pager')
            ->setCollection($this->getStoreCollection());
        $this->setChild('pager', $pager);
        $this->getStoreCollection()->load();
        return $this;
	}
	
	public function getStoreCollection()
	{
		
		$collection = Mage::getModel('storelocator/store')->getCollection()
						->addFieldToFilter('status',1)
						->addStoreFilter(Mage::app()->getStore())
						->setOrder('store_name','ASC');
		
		if($this->getRequest()->getParam('store')) {
			$collection = $collection->addFieldToFilter('store_id',$this->getRequest()->getParam('store'));
		}
		if ($this->getRequest()->getParam('country'))
		{				
			$country = $this->getRequest()->getParam('country');
			$collection = $collection->addFieldToFilter('country',array('like'=>'%'.$country.'%'));
		}
		if ($this->getRequest()->getParam('state'))
		{				
			$state = $this->getRequest()->getParam('state');
			$state = trim($state);
			$collection = $collection->addFieldToFilter('state',array('like'=>'%'.$state.'%'));
		}
		if ($this->getRequest()->getParam('city'))
		{				
			$city = $this->getRequest()->getParam('city');
			$city = trim($city);
			$collection = $collection->addFieldToFilter('city',array('like'=>'%'.$city.'%'));
		}
		if ($this->getRequest()->getParam('name'))
		{				
			$name = $this->getRequest()->getParam('name');
			$name = trim($name);
			$collection = $collection->addFieldToFilter('store_name',array('like'=>'%'.$name.'%'));
		}

		$this->setData('store_collection', $collection);
		
		return $this->getData('store_collection');
	}
	public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
	
	public function getCoordinates($store)
	{
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

	public function getCountryName($country)
	{
		$country = Mage::getResourceModel('directory/country_collection')
						->addFieldToFilter('country_id',$country)
						->getFirstItem();
		return $country->getName();				
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