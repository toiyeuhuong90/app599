<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 10/16/2015
 * Time: 4:06 PM
 */
class QSoft_AppApi_Model_Resource_Relationship extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('qsoft_appapi/relationship','id');
    }
}