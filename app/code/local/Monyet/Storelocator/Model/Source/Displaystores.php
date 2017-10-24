<?php

class Monyet_Storelocator_Model_Source_Displaystores
{
    public function toOptionArray()
	{
		return array(
            array('value'=>2, 'label'=>Mage::helper('storelocator')->__('Top Link')),
            array('value'=>1, 'label'=>Mage::helper('storelocator')->__('Footer Link')),
            array('value'=>0, 'label'=>Mage::helper('storelocator')->__('Not shown')),
		);	
	}
}