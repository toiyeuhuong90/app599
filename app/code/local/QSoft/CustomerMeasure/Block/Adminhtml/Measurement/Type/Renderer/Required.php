<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/14/2016
 * Time: 2:54 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Type_Renderer_Required extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $html = '';
        $is_required = $row->getData('is_required');


        $html .= ($is_required) ? $this->__('Yes') : $this->__('No');

        echo $html;
    }
}