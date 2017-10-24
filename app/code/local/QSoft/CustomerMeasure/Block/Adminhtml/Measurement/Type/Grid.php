<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 3:52 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Measurement_Type_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('measurement_type_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);

    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('qsoft_customermeasure/type')->getCollection();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('measure_id', array(
            'header' => Mage::helper('qsoft_customermeasure')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'type' => 'number',
            'index' => 'measure_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('qsoft_customermeasure')->__('Title'),
            'index' => 'title',
        ));

        $this->addColumn('unit', array(
            'header' => Mage::helper('qsoft_customermeasure')->__('Type of Unit'),
            'index' => 'unit',
            'type'      =>  'options',
            'options'   =>  $this->typeOfUnit(),
        ));

        $this->addColumn('gender', array(
            'header' => Mage::helper('qsoft_customermeasure')->__('Type of Gender'),
            'type'      =>  'options',
            'index' => 'gender',
            'options'   =>  $this->typeOfGender(),
        ));

        $this->addColumn('type_of_measurement', array(
            'header' => Mage::helper('qsoft_customermeasure')->__('Type of Measurement'),
            'type'      =>  'options',
            'index' => 'type_of_measurement',
            'options'   =>  $this->typeOfMeasurement(),
        ));

        $this->addColumn('description', array(
            'header' => Mage::helper('qsoft_customermeasure')->__('Measurement Type Description'),
            'index' => 'description',
        ));

        $this->addColumn('show_in_dashboard', array(
            'header' => Mage::helper('qsoft_customermeasure')->__('Show in dashboard'),
            'type'      =>  'options',
            'index' => 'show_in_dashboard',
            'options'   =>  $this->showInDashboard(),
        ));
        $this->addColumn('video_url',
            array(
                'header'         => Mage::helper('qsoft_customermeasure')->__('Video'),
                'width'          => '170px',
                'align'          => 'center',
                'index'          => 'video_url',
                'filter'         => false,
                'sortable'       => false,
                'frame_callback' => array($this, 'showVideo')
            ))
        ;


        $this->addColumn('action',
            array(
                'header' => Mage::helper('qsoft_customermeasure')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('qsoft_customermeasure')->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    public function showVideo($value, $row, $column)
    {
        $html = "No video";
        if($value){
            $youtubes = explode('watch?v=', $value);
            $html = "<iframe width=\"280\" height=\"157\" src=\"https://www.youtube.com/embed/{$youtubes[1]}\" frameborder=\"0\" allowfullscreen></iframe>";
        }

        return $html;
    }

    protected function typeOfUnit() {
        $result = array();
        $array = array('1' => $this->__('Weight'), '2' => $this->__('Height'));
        foreach ($array as $v=>$k) {
            $result[$v] = $k;
        }
        return $result;
    }

    protected function typeOfGender() {
        $result = array();
        $array = array('0' => $this->__('Both'), '1' => $this->__('Male'), '2' => $this->__('Female'));
        foreach ($array as $v=>$k) {
            $result[$v] = $k;
        }
        return $result;
    }

    protected function typeOfMeasurement() {
        $result = array();
        $array = array('1' => $this->__('Fitness'), '2' => $this->__('Garment'));
        foreach ($array as $v=>$k) {
            $result[$v] = $k;
        }
        return $result;
    }

    protected function showInDashboard() {
        $result = array();
        $array = array('0' => $this->__('No'), '1' => $this->__('Yes'));
        foreach ($array as $v=>$k) {
            $result[$v] = $k;
        }
        return $result;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');

        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_type', array(
            'label' => Mage::helper('qsoft_customermeasure')->__('Remove Type'),
            'url' => $this->getUrl('*/adminhtml_measurement_type/massDelete'),
            'confirm' => Mage::helper('qsoft_customermeasure')->__('Are you sure you want to delete the selected Measurement Type(s)?')
        ));

        return $this;
    }


    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}