<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 20/07/2016
 * Time: 09:48
 */ 
class QSoft_ProductDesign_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Text extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Text {
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('qsoft/productdesign/product/edit/options/type/text.phtml');
    }
}