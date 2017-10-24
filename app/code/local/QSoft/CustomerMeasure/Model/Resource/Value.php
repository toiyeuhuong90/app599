<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/15/2016
 * Time: 3:00 PM
 */
class QSoft_CustomerMeasure_Model_Resource_Value extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        // TODO: Implement _construct() method.
        $this->_init('qsoft_customermeasure/customer', 'id');
    }
}