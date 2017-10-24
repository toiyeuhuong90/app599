<?php

/**
 * QSoft Vietnam
 * http://www.qsoftvietnam.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@qsoftvietnam.com so we can send you a copy immediately.
 *
 * @category    QSoft
 * @package     QSoft_ProductDesign
 * @author      Tuyen Nguyen <tuyennn@qsoftvietnam.com>
 * @copyright   Copyright (c) 2016 (http://www.qsoftvietnam.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class QSoft_ProductDesign_Block_Adminhtml_Inspireme_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('inspireMeGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        //$this->setTemplate('qsoft/productdesign/inspireme/grid.phtml');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('productdesign/inspireme')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper("productdesign")->__('ID'),
            'align' => 'right',
            'width' => '20px',
            'type' => 'number',
            'index' => 'id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper("productdesign")->__('Name'),
            'index' => 'name',
        ));
        
        $this->addColumn('avartar_image', array(
            'header' => Mage::helper("productdesign")->__('Avatar Image'),
            'index' => 'avartar_image',
            'align' => 'center',
            'renderer' => new QSoft_ProductDesign_Block_Adminhtml_Inspireme_Edit_Grid_Renderer_Thumb(),
        ));

        $this->addColumn('product', array(
            'header' => Mage::helper("productdesign")->__('Product'),
            'index' => 'product_id',
            'renderer' => new QSoft_ProductDesign_Block_Adminhtml_Inspireme_Edit_Grid_Renderer_Product(),
        ));
        $this->addColumn('sort_order', array(
            'header' => Mage::helper("productdesign")->__('Sort Order'),
            'index' => 'sort_order',
            'width' => '50px'
        ));

        $this->addColumn('action', array(
                'header' => $this->__('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => $this->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )

                ),

                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            )
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}