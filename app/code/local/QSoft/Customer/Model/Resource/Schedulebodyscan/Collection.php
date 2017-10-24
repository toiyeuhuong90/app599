<?php

class QSoft_Customer_Model_Resource_Schedulebodyscan_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('qsoft_customer/schedulebodyscan');
    }

    public function addNameToSelect(){
        $this->getSelect()->columns(array('name' => 'concat(firstname,\' \',lastname)'));
        return $this;
    }
}
