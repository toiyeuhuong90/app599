<?php

class QSoft_ProductDesign_Block_Adminhtml_Frontendgroup_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
        parent::__construct();
        $this->setId('groupGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setTemplate('qsoft/productdesign/frontendgroup/options.phtml');
    }

    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('productdesign/frontendgroup')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('id', array(
                'header'    => Mage::helper("productdesign")->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'id',
        ));

        $parents = Mage::helper('productdesign/frontendgroup')->getGroupTree();

        $this->addColumn('parent_id', array(
            'header'    => Mage::helper("productdesign")->__('Parent'),
            'align'     =>'left',
            'index'     => 'parent_id',
            'type'      => 'options',
            'options'   => $parents,
        ));

        $this->addColumn('name', array(
                'header'    => Mage::helper("productdesign")->__('Name'),
                'align'     =>'left',
                'index'     => 'name',
        ));
		

        $this->addColumn('action',
                array(
                'header'    =>  Mage::helper("productdesign")->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                        array(
                                'caption'   => Mage::helper("productdesign")->__('Edit'),
                                'url'       => array('base'=> '*/*/edit'),
                                'field'     => 'id'
                        ),
                        array(
                                'caption'   => Mage::helper("productdesign")->__('Delete'),
                                'url'       => array('base'=> '*/*/delete'),
                                'field'     => 'id'
                        )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}