<?php

class Magestore_Megamenu_Model_System_Config_Source_Menutype
{
    //list type of menu when show in fontend
    public function toOptionArray(){
        return array(
            array('value'=>1, 'label'=>'Horizontal'),
            array('value'=>2, 'label'=>'Vertical')
        );
    }
}