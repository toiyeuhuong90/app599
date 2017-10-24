<?php

class QSoft_CustomerMeasure_Model_Source_Weight
{
    public function toOptionArray()
    {
        $out = array();
        $out[] = array('value' => 1, 'label' => 'Kg');
        $out[] = array('value' => 2, 'label' => 'Lbs');
        return $out;
    }

}