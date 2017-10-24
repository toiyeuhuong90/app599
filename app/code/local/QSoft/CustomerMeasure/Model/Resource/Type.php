<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 3:20 PM
 */
class QSoft_CustomerMeasure_Model_Resource_Type extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        // TODO: Implement _construct() method.
        $this->_init('qsoft_customermeasure/measure', 'measure_id');
    }
}