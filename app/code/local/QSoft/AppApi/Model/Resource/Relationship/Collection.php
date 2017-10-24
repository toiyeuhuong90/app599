<?php

class QSoft_AppApi_Model_Resource_Relationship_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('qsoft_appapi/relationship');
    }

}
