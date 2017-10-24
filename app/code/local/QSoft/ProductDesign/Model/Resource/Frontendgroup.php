<?php
class QSoft_ProductDesign_Model_Resource_Frontendgroup extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct() {
        $this->_init('productdesign/frontendgroup','id');
    }
}