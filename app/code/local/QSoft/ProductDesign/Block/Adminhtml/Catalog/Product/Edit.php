<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 11/18/2015
 * Time: 8:49 AM
 */ 
class QSoft_ProductDesign_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Catalog_Product_Edit {
//    public function __construct()
//    {
//        parent::__construct();
//        $this->setTemplate('qsoft/productoptions/catalog/product/edit.phtml');
//        $this->setId('product_edit');
//    }
    protected function _prepareLayout()
    {
        if (!$this->getRequest()->getParam('popup')) {
            $this->setChild('back_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label'     => Mage::helper('catalog')->__('Back'),
                        'onclick'   => 'setLocation(\''
                            . $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store', 0))).'\')',
                        'class' => 'back'
                    ))
            );
        } else {
            $this->setChild('back_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label'     => Mage::helper('catalog')->__('Close Window'),
                        'onclick'   => 'window.close()',
                        'class' => 'cancel'
                    ))
            );
        }

        if (!$this->getProduct()->isReadonly()) {
            $this->setChild('reset_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label'     => Mage::helper('catalog')->__('Reset'),
                        'onclick'   => 'setLocation(\''.$this->getUrl('*/*/*', array('_current'=>true)).'\')'
                    ))
            );

            $this->setChild('save_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label'     => Mage::helper('catalog')->__('Save'),
                        'onclick'   => 'productForm.submit()',
                        'class' => 'save'
                    ))
            );
        }

        if (!$this->getRequest()->getParam('popup')) {
            if (!$this->getProduct()->isReadonly()) {
                $this->setChild('save_and_edit_button',
                    $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label'     => Mage::helper('catalog')->__('Save and Continue Edit'),
                            'onclick'   => 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
                            'class' => 'save'
                        ))
                );
            }
            if ($this->getProduct()->isDeleteable()) {

                $confirmationMessage = Mage::helper('core')->jsQuoteEscape(
                    Mage::helper('catalog')->__('Are you sure?')
                );
                $this->setChild('delete_button',
                    $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label'     => Mage::helper('catalog')->__('Delete'),
                            'onclick'   => 'confirmSetLocation(\'' . $confirmationMessage
                                . '\', \'' . $this->getDeleteUrl() . '\')',
                            'class'  => 'delete'
                        ))
                );
            }

            if ($this->getProduct()->isDuplicable()) {
                $this->setChild('duplicate_button',
                    $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label'     => Mage::helper('catalog')->__('Duplicate'),
                            'onclick'   => 'setLocation(\'' . $this->getDuplicateUrl() . '\')',
                            'class'  => 'add'
                        ))
                );
            }
            if($this->getProduct()){
                $this->setChild('import_options_button',
                    $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Import Option'),
                            'class' => 'import',
                            'id'    => 'import_product_option',
                            'onclick' => 'return onImportOptions();'
                        ))
                );
            }
        }

        return $this;
    }
    public function getDeleteButtonHtml()
    {
        return $this->getImportOptionsButtonHtml() . $this->getChildHtml('delete_button');
    }
    public function getImportOptionsButtonHtml(){
        return $this->getChildHtml('import_options_button').'<input type="hidden" id="admin_import_url" value="' . $this->getUrl('admin_productoptions/adminhtml_ajax/import').'"/>
        <input type="hidden" id="product_id" value="' . Mage::registry('current_product')->getId() .'"/>';
    }
}