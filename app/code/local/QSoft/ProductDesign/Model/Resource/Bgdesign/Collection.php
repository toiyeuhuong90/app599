<?php
class QSoft_ProductDesign_Model_Resource_Bgdesign_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    public function _construct() {
        parent::_construct();
        $this->_init('productdesign/bgdesign');
    }
}