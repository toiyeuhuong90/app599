<?php
class QSoft_CustomerMeasure_Model_Source_Height 
{
    public function toOptionArray()
    {
        $out = array();
        $out[] = array('value' => 1, 'label' => 'Cm');
        $out[] = array('value' => 2, 'label' => 'Inch');
        return $out;
    }
    
}