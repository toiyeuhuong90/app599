<?php

class QSoft_ProductDesign_Helper_Frontendgroup extends Mage_Core_Helper_Abstract
{

    private $_effectOptions;
	private $outtree = array();
    private $groupTree = array();


    public function getSelectcat($parent=null){
        $exits = array();
        if(is_null($parent)){
            $parent = 0;

            $this->outtree['value'][] = '0';
            $this->outtree['label'][] = 'Root';
        }
        $this->drawSelect($parent);

        foreach ($this->outtree['value'] as $k => $v){
            $out[] = @array('value'=>$v, 'label'=>$this->outtree['label'][$k], 'label_origin'=>$this->outtree['label_origin'][$k]);
        }
        return $out;
    }

    public function drawSelect($pid=0, $sep=1){
        $spacer = '';
        for ($i = 0; $i <= $sep; $i++){
            $spacer.= '----';
        }
        $items = $this->getChildrens($pid);
        if(count($items) > 0 ){
            foreach ($items as $item){
                $this->outtree['value'][$item['id']] = $item['id'];
                $this->outtree['label'][$item['id']] = $spacer . $item['name'];
                $this->outtree['label_origin'][$item['id']] = $item['name'];
                //$this->outtree['type'][$item['id']] = explode(',',$item['apply_product_type']);
                $child = $this->getChildrens($item['id']);
                if(!empty($child)){
                    $this->drawSelect($item['id'], $sep + 1);
                }
            }
        }
        return;
    }
    
	public function getChildrens($pid=0){
		$out = array();
        $collection = Mage::getModel('productdesign/frontendgroup')->getCollection()
        	->addFieldToFilter('parent_id', array('in'=>$pid) )
			->setOrder('sort_order', 'asc');
		foreach ($collection as $item){
			$out[] = $item->getData();
		}
		return $out;
	}

    public function getGroupTree($parent=null){
        if(is_null($parent)){
            $parent = 0;
            $this->groupTree['0'] = 'Root';
        }
        $this->drawGroupItem($parent);
        return $this->groupTree;
    }

	public function hasChildrens($pid=0){
        $collection = Mage::getModel('productdesign/frontendgroup')->getCollection()
        	->addFieldToFilter('pid', array('in'=>$pid) )
			->setOrder('sort_order', 'asc')
			->load();
		if($collection->count() > 0){
			return true;
		}
		return false;
	}


    public function drawGroupItem($pid=0, $sep=1){
        $spacer = '';
        for ($i = 0; $i <= $sep; $i++){
            $spacer.= '----';
        }
        $items = $this->getChildrens($pid);
        if(count($items) > 0 ){
            foreach ($items as $item){
                $this->groupTree[$item['id']] = $spacer . $item['name'];
                $child = $this->getChildrens($item['id']);
                if(!empty($child)){
                    $this->drawGroupItem($item['id'], $sep + 1);
                }
            }
        }
        return;
    }

    public function getSelectcat1($parent=null){
//        if(is_null($parent)){
//            $parent = 0;
//            $this->outtree['value'][] = '0';
//            $this->outtree['label'][] = 'Root';
//        }
//        $this->drawSelect1($parent);
//
//        foreach ($this->outtree['value'] as $k => $v){
//            $out[] = array('value'=>$v, 'label'=>$this->outtree['label'][$k]);
//        }
        $out = array(
            'color'=>'Color',
            'size'=>'Size',
            'pad'=>'Pad',
            'multi'=>'Product'
        );
        return $out;
    }

    public function drawSelect1($pid=0, $sep=1){
        $spacer = '';
        for ($i = 0; $i <= $sep; $i++){
            $spacer.= '----';
        }
        $items = $this->getChildrens($pid);
        if(count($items) > 0 ){
            foreach ($items as $item){
                $this->outtree['value'][$item['id']] = $item['id'];
                $this->outtree['label'][$item['id']] = $spacer . $item['name'];
            }
        }
        return;
    }
}