<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 12/08/2016
 * Time: 11:32
 */ 
class QSoft_ProductDesign_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Select extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Select {
    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('qsoft/productdesign/product/edit/options/type/select.phtml');
        $this->setCanEditPrice(true);
        $this->setCanReadPrice(true);
    }
}