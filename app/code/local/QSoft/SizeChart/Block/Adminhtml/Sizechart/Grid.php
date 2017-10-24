<?php

class QSoft_SizeChart_Block_Adminhtml_Sizechart_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("sizechartGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("sizechart/sizechart")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn("id", array(
            "header" => Mage::helper("sizechart")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "id",
        ));

        $this->addColumn("name", array(
            "header" => Mage::helper("sizechart")->__("Name"),
            "index" => "name",
        ));
        $this->addColumn("main_image", array(
            "header" => Mage::helper("sizechart")->__("Main Image"),
            "index" => "main_image",
            'renderer' => new QSoft_ProductDesign_Block_Adminhtml_Inspireme_Edit_Grid_Renderer_Thumb(),
        ));
        $this->addColumn("image_cm", array(
            "header" => Mage::helper("sizechart")->__("Image for Cm"),
            "index" => "image_cm",
            'renderer' => new QSoft_ProductDesign_Block_Adminhtml_Inspireme_Edit_Grid_Renderer_Thumb(),
        ));
        $this->addColumn("image_inch", array(
            "header" => Mage::helper("sizechart")->__("Image for Inch"),
            "index" => "image_inch",
            'renderer' => new QSoft_ProductDesign_Block_Adminhtml_Inspireme_Edit_Grid_Renderer_Thumb(),
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }


}