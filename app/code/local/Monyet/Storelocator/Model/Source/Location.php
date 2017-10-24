<?php

class Monyet_Storelocator_Model_Source_Location extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    public function getAllOptions()
    {
        $out = array(array('value' => null, 'label' => '-- Select One --'));
        $collection = Mage::getModel('storelocator/store')->getCollection();
        $collection->setOrder('store_name', 'ASC');
        foreach ($collection as $locator){
            $out[] = array('value' => $locator->getId(), 'label' => $locator->getStoreName());
        }

        return $out;
    }

}