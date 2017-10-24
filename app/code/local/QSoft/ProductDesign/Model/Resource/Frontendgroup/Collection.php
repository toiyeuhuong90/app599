<?php
class QSoft_ProductDesign_Model_Resource_Frontendgroup_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
	protected $options = null;
    public function _construct() {
        parent::_construct();
        $this->_init('productdesign/frontendgroup');
    }

    public function addApplyToFilter($type)
    {
        $this->addFieldToFilter('apply_product_type', array('like'=> '%'.$type.'%'));
        return $this;
    }
    
    public function toOptionArray(){
    	$options = array();
    	if(is_null($this->options)){
    		$this->options[] = array(
    				'label'=> 'Root',
    				'value' => 0
    		);
	    	foreach($this as $item){
	    		$this->options[] = array(
	    			'label'=> $item->getName(),
	    			'value' => $item->getId()
	    		);
	    	}
    	}
    	return $this->options;
    }
}