<?php
class QSoft_ProductDesign_Model_Resource_Bgdesign extends Mage_Core_Model_Resource_Db_Abstract
{
	public function _construct() {
        $this->_init('productdesign/bgdesign','id');
    }
}