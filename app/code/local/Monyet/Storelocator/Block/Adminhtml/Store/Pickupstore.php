<?php

class Monyet_Storelocator_Block_Adminhtml_Sales_Tab_Storepickup 
		extends Mage_Adminhtml_Block_Widget_Form
		implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('monyet/storelocator/storelocator.phtml');
	}
	
	public function getTabLabel()	{
		return Mage::helper('sales')->__('Store Pickup');
	}

	public function getTabTitle() {
		return Mage::helper('sales')->__('Store Pickup');
	}
	
	public function canShowTab()	{
		if($this->getStorepickup())	
			return true;
		else
			return false;
		}
	
	public function isHidden()	{
		if($this->getStorepickup())
			return false;
		else
			return true;
	}		
	
	public function getStorepickup()
	{
		if(!$this->hasData('storelocator'))
		{
			$storelocator = null;
			
			$order = $this->getOrder();
			
			if (!$order) 
			{
				$this->setData('storelocator',null);
				return $this->getData('storelocator');
			}
			
			$order_id = $order->getId();
			
			$storelocator = Mage::helper('storelocator')->getStorepickupByOrderId($order_id);
			$this->setData('storelocator',$storelocator);
		}
		return $this->getData('storelocator');
	}
	
	public function getOrder()
    {       
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order');
        }
        if (Mage::registry('order')) {
            return Mage::registry('order');
        }
       
    }

	public function getShippingTime($order_id)
	{		
		$time = null;
		if ($order_id) { 
			$storeorder = Mage::getModel('storelocator/storeorder')->getCollection()
						->addFieldToFilter('order_id',$order_id)
						->getFirstItem();
		}					
		if ($storeorder)
			$time = $storeorder->getShippingTime();	
		return 	$time;		
	}	
	
	public function getShippingDate($order_id)
	{		
		$date = null;
		if ($order_id) {
			$storeorder = Mage::getModel('storelocator/storeorder')->getCollection()
						->addFieldToFilter('order_id',$order_id)
						->getFirstItem();
		}				
		if ($storeorder)
			$date = $storeorder->getShippingDate();
		return 	$date;		
	}	
}