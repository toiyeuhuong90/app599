<?php
class QSoft_ProductDesign_Block_Adminhtml_Catalog_Product_Edit_Tab_Options extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options {
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('qsoft/productoptions/product/edit/options.phtml');
    }

    protected function _prepareLayout()
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Add New Option'),
                    'class' => 'add',
                    'id'    => 'add_new_defined_option'
                ))
        );
        if(Mage::registry('current_product')){
            $this->setChild('import_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label' => Mage::helper('catalog')->__('Import Option'),
                        'class' => 'import',
                        'id'    => 'import_product_option',
                        'onclick' => 'return onImportOptions();'
                    ))
            );
        }

        $this->setChild('options_box',
            $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_options_option')
        );

        return $this;
    }
}