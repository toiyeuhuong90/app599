<?php
class QSoft_ProductDesign_Model_Frontendgroup extends Mage_Core_Model_Abstract
{
    protected $_priceConfig;
    protected $_parent;
    protected $_chidrens;
	public function _construct() {
        parent::_construct();
        $this->_init('productdesign/frontendgroup');
    }

    public function getPriceConfig(){
        if(is_null($this->_priceConfig)){
            $priceConfig = $this->getData('price_config');
            if(!empty($priceConfig)){
                $priceConfig = unserialize($priceConfig);
                $priceConfig = array_filter($priceConfig);
                $this->_priceConfig = $priceConfig;
            }
        }
        return $this->_priceConfig;
    }

    public function getPriceConfigFormated(){
        $result = array();
        if($this->getPriceConfig()){
            $priceConfig = $this->getPriceConfig();
            foreach($priceConfig as $rate){
                $result[] = $rate;
            }
        }
        return $result;
    }

    public function getParentGroup()
    {
        if(is_null($this->_parent)){
            if($this->hasParentId()){
                $this->_parent = Mage::getModel('picasso/groupOption')->load($this->getParentId());
            }
        }
        return $this->_parent;
    }

    public function getChildrens()
    {
        if(is_null($this->_chidrens)){
            $this->_chidrens = Mage::getResourceModel('picasso/groupOption_collection')->addFieldToFilter('parent_id',$this->getId());
        }
        return $this->_chidrens;
    }
}