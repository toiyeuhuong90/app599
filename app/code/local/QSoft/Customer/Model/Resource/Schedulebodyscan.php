<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 10/16/2015
 * Time: 4:06 PM
 */
class QSoft_Customer_Model_Resource_Schedulebodyscan extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('qsoft_customer/schedulebodyscan','id');
    }
}