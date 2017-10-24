<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 16/06/2016
 * Time: 18:01
 */ 
class QSoft_ProductDesign_Model_Catalog_Product_Option extends Mage_Catalog_Model_Product_Option {
    const PRODUCT_DESIGN = 'imagedesign';
    const OPTION_TYPE_DESIGN_IMAGE = 'image';

    public function getGroupByType($type = null)
    {
        if (is_null($type)) {
            $type = $this->getType();
        }
        $optionGroupsToTypes = array(
            self::OPTION_TYPE_FIELD => self::OPTION_GROUP_TEXT,
            self::OPTION_TYPE_AREA => self::OPTION_GROUP_TEXT,
            self::OPTION_TYPE_FILE => self::OPTION_GROUP_FILE,
            self::OPTION_TYPE_DROP_DOWN => self::OPTION_GROUP_SELECT,
            self::OPTION_TYPE_RADIO => self::OPTION_GROUP_SELECT,
            self::OPTION_TYPE_CHECKBOX => self::OPTION_GROUP_SELECT,
            self::OPTION_TYPE_MULTIPLE => self::OPTION_GROUP_SELECT,
            self::OPTION_TYPE_DATE => self::OPTION_GROUP_DATE,
            self::OPTION_TYPE_DATE_TIME => self::OPTION_GROUP_DATE,
            self::OPTION_TYPE_TIME => self::OPTION_GROUP_DATE,
            self::OPTION_TYPE_DESIGN_IMAGE => self::PRODUCT_DESIGN
        );

        return isset($optionGroupsToTypes[$type])?$optionGroupsToTypes[$type]:'';
    }

    /**
     * Group model factory
     *
     * @param string $type Option type
     * @return Mage_Catalog_Model_Product_Option_Group_Abstract
     */
    public function groupFactory($type)
    {
        if( $type === self::OPTION_TYPE_DESIGN_IMAGE ){
            return Mage::getModel('productdesign/catalog_product_option_type_productdesign');
        }
        return parent::groupFactory($type);
    }
}