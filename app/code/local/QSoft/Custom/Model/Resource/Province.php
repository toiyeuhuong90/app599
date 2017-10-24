<?php

class QSoft_Custom_Model_Resource_Province extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('qsoft_custom/province', 'id');
    }

}