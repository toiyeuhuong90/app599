<?php

class Monyet_Storelocator_Model_Source_Searchfield
{
    public function toOptionArray()
	{
		return array(
            array('value'=>'name', 'label'=>Mage::helper('storelocator')->__('Store Name')),
            array('value'=>'city', 'label'=>Mage::helper('storelocator')->__('City')),
            array('value'=>'state', 'label'=>Mage::helper('storelocator')->__('State/Province')),
            array('value'=>'country', 'label'=>Mage::helper('storelocator')->__('Country')),
        );	
	}
}