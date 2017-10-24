<?php
class QSoft_Custom_Block_Adminhtml_Location_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('locationGrid');
        $this->setDefaultSort('city_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        //$this->setTemplate('qsoft/productdesign/inspireme/grid.phtml');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('qsoft_custom/district')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header' => Mage::helper("qsoft_custom")->__('Province'),
            'index' => 'city_id',
            'width' => '200',
            'type'      =>  'options',
            'options'   =>  Mage::helper('qsoft_custom')->getProvinceTypeToOptionArray(),
        ));

        $this->addColumn('sort_order', array(
            'header' => Mage::helper("qsoft_custom")->__('District'),
            'index' => 'name',
            'width' => '500'
        ));

        $this->addColumn('avartar_image', array(
            'header' => Mage::helper("qsoft_custom")->__('Ward'),
            'index' => 'avartar_image',
            'align' => 'center',
            'renderer' => new QSoft_Custom_Block_Adminhtml_Location_Grid_Renderer_Ward(),
            'filter'=>false
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId(),'city_id'=>$row->getCityId()));
    }

}