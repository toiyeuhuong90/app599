<?php
class QSoft_ProductDesign_Model_Source_Chart extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        $out = array();
        $collection = Mage::getModel('cms/block')->getCollection();
        $collection->addFieldToFilter('is_size_chart',1);
        foreach ($collection as $item){
            $out[] = array('value'=>$item->getIdentifier(), 'label'=>$item->getTitle());
        }
        return $out;
    }
    
}