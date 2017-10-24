<?php

class Monyet_Storelocator_Model_Source_Selectorpayment
{
    public function toOptionArray()
    {
        return array(
            array('value'=>0, 'label'=>Mage::helper('storelocator')->__('All Allowed Payments')),
            array('value'=>1, 'label'=>Mage::helper('storelocator')->__('Specific Payments')),
        );
    }
}
