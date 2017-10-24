<?php

class QSoft_Custom_Block_Adminhtml_Location_Grid_Renderer_Ward extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $html = '<ul style="text-align: left;">';
        $read = Mage::getModel('core/resource')->getConnection('core_read');
        $sql = 'select name from viettelpost_ward where district_id=?';
        $wards =  $read->fetchAll($sql,array($row->getId()));
        $k = 1;
        foreach ($wards as $ward){
            $html .= '<li style="text-align: left;padding-left: 30px">' . $k . '. ' . $ward['name'] . '</li>';
            $k++;
        }
        $html .= '</ul>';
        return $html;
    }
}