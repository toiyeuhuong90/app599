<?php 
class QSoft_ProductDesign_Model_Catalog_Product_Option_Type_Productdesign extends Mage_Catalog_Model_Product_Option_Type_Select{
    /**
     * Check if option has single or multiple values selection
     *
     * @return boolean
     */
    protected function _isSingleSelection()
    {
        return true;
    }

   
}