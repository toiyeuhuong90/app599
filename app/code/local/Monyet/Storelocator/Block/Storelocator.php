<?php
class Monyet_Storelocator_Block_Storelocator extends Mage_Core_Block_Template
{
	public function __construct()
	{	
		parent::__construct();

	}
	
	public function _prepareLayout()
    {
		$return = parent::_prepareLayout();
		
		$listStore = $this->getStoreByLocation();
			
		$this->setListStoreLocation($listStore);
		
		$this->setTemplate('monyet/storelocator/selector.phtml');
		
		return $return;
	}
	
	
	public function getStoreByLocation()
	{
		
		if(! $this->hasData('storecollection'))
		{
			if($this->getShippingModel()->getConfigData('active_gapi'))	
			{
				$stores =  Mage::getSingleton('storelocator/store')->filterStoresUseGAPI();
			} else {
				$stores =  Mage::getSingleton('storelocator/store')->toListOptions();
			}
			$this->setData('storecollection',$stores);
		}
		return $this->getData('storecollection');
	}

	public function getStoreCollection()
	{
		return $collection = Mage::getModel('storelocator/store')->getCollection()
							->addStoreFilter(Mage::app()->getStore())
							->addFieldToFilter('status',1);
	}

	public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }	
}