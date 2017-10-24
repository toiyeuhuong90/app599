<?php

class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Type_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $html = '<a href="javascript:void(0);" onclick="viewHistory('.$row->getId().');">View</a>';
        return $html;
    }
}