<?php

class QSoft_Customer_Model_Source_Interested extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    public function getAllOptions()
    {
        $out = array();
        $out[] = array('value' => 'RUN', 'label' => 'RUN');
        $out[] = array('value' => 'BIKE', 'label' => 'BIKE');
        $out[] = array('value' => 'SWIM', 'label' => 'SWIM');
        $out[] = array('value' => 'TRIATHLON', 'label' => 'TRIATHLON');
        $out[] = array('value' => 'CUSTOM DESIGN', 'label' => 'CUSTOM DESIGN');
        $out[] = array('value' => 'NEWSLETTER', 'label' => 'NEWSLETTER');
        return $out;
    }

}