<?php

class QSoft_Customer_Block_Adminhtml_Schedulebodyscan_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("schedulebodyscanGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("qsoft_customer/schedulebodyscan")->getCollection();
        $collection->addNameToSelect();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn("id", array(
            "header" => Mage::helper("qsoft_customer")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "id",
        ));

        $this->addColumn("name", array(
            "header" => Mage::helper("qsoft_customer")->__("Name"),
            "index" => "name",
        ));

        $this->addColumn("email", array(
            "header" => Mage::helper("qsoft_customer")->__("Email"),
            "index" => "email",
        ));

        $this->addColumn("telephone", array(
            "header" => Mage::helper("qsoft_customer")->__("Telephone"),
            "index" => "telephone",
        ));

        $this->addColumn("note", array(
            "header" => Mage::helper("qsoft_customer")->__("Message"),
            "index" => "note",
        ));

        $this->addColumn("address", array(
            "header" => Mage::helper("qsoft_customer")->__("Address"),
            "index" => "note",
            "type" => "address",
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('qsoft_customer')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => self::getOptionArray5(),
        ));

        $this->addColumn('book_time', array(
            'header' => Mage::helper('qsoft_customer')->__('Book Time'),
            'index' => 'book_time',
            'type' => 'datetime',
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('qsoft_customer')->__('Created At'),
            'index' => 'created_at',
            'type' => 'datetime',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }


    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_schedulebodyscan', array(
            'label' => Mage::helper('qsoft_customer')->__('Remove schedulebodyscan'),
            'url' => $this->getUrl('*/schedulebodyscan/massRemove'),
            'confirm' => Mage::helper('qsoft_customer')->__('Are you sure?')
        ));
        return $this;
    }

    public function getOptionArray5(){
        return array(
            '0'=>'Pending',
            '1'=>'Complete'
        );
    }
    static public function getValueArray5()
    {
        $data_array = array();
        foreach (self::getOptionArray5() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }

}