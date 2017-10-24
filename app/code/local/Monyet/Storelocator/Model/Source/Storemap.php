<?php

class Monyet_Storelocator_Model_Source_Storemap
{
    public function toOptionArray()
	{
		return array(
            array('value'=>2, 'label'=>Mage::helper('storelocator')->__('Selector')),
            array('value'=>1, 'label'=>Mage::helper('storelocator')->__('Map')),
        );	
	}
}