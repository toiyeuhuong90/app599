<?php
class QSoft_ProductDesign_Block_Adminhtml_Frontendgroup extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        $this->_controller = 'adminhtml_frontendgroup';
        $this->_blockGroup = 'productdesign';
        $this->_headerText = Mage::helper("productdesign")->__('Frontend Group Manager');
        parent::__construct();

        //$this->setTemplate('my_productoptions/group.phtml');
    }

    protected function _prepareLayout() {
        $this->setChild('add_new_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper("productdesign")->__('Add New Group'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/add')."')",
                'class'   => 'add'
                ))
        );
        $this->setChild('grid', $this->getLayout()->createBlock('productdesign/adminhtml_frontendgroup_grid', 'group.grid'));
        return parent::_prepareLayout();
    }

    public function getAddNewButtonHtml() {
        return $this->getChildHtml('add_new_button');
    }

    public function getGridHtml() {
        return $this->getChildHtml('grid');
    }
}