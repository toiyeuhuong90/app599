<?php
class QSoft_Custom_Model_Resource_Ward_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('qsoft_custom/ward');
    }

}