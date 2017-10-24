<?php

/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 10/08/2016
 * Time: 14:50
 */
class QSoft_ProductDesign_Model_Catalog_Product extends Mage_Catalog_Model_Product
{
    public function getColorMobileTab(){
        $result = array();
        $options = $this->getOptions();
        foreach ($options as $option){
            if($option->getFrontendGroupId()=='color'){
                $groups = $this->_getValueGroups($option);
                foreach ($groups as $group){
                    $result['groups'][] = $group;
                    $result['type'] = 'color';
                    $result['option'] = $option;
                    $result['label'] = $group;
                }
                break;
            }
        }
        return $result;
    }

    public function getDefaultImageZoom(){
        $config = array();
        foreach ($this->getOptions() as $option) {
            if ($option->getGroupByType() == QSoft_ProductDesign_Model_Catalog_Product_Option::PRODUCT_DESIGN) {
                foreach ($option->getValues() as $value) {
                    if($value->getIsDefault()){
                        $imagesDesign = Mage::helper('core')->jsonDecode($value->getDesignImages());
                        $k=1;
                        foreach ($imagesDesign as $imageDesign) {
                            $img = false;
                            if($imageDesign['value']){
                                $arrPath = explode('media', $imageDesign['value']);
                                $img = str_replace('index.php/', '', $this->getUrl() . 'media/' . $arrPath[1]);
                            }

                            if($img){
                                $config[$k]['image'] = Mage::helper('productdesign/image')->getResizedImage($img, 400, 400);
                                $config[$k]['zoom-image'] = Mage::helper('productdesign/image')->getResizedImage($img, 1000, 1000);
                            }

                            $k++;
                            //$config[$id][$imageDesign['name']] = Mage::helper('productdesign/image')->getResizedImage($imageDesign['value']);
                        }
                        break;
                    }
                }
                break;
            }
        }
        return $config;
    }


    public function getOptionTabs()
    {
        $result = array();
        $options = $this->getOptions();
        foreach ($options as $option){
            if($option->getFrontendGroupId()=='color'){
                if($this->_getGroupType($option, 'color')){
                    $result[$option->getId() . '-' . 'color']['group'] = 'color';
                    $result[$option->getId() . '-' . 'color']['class'] = 'group-is-' . 'color';
                    $result[$option->getId() . '-' . 'color']['type'] = 'color';
                    $result[$option->getId() . '-' . 'color']['option'] = $option;
                    $result[$option->getId() . '-' . 'color']['label'] = ucfirst('color');
                }

                if($this->_getGroupType($option, 'pattern')){
                    $result[$option->getId() . '-' . 'pattern']['group'] = 'pattern';
                    $result[$option->getId() . '-' . 'pattern']['class'] = 'group-is-' . 'pattern';
                    $result[$option->getId() . '-' . 'pattern']['type'] = 'color';
                    $result[$option->getId() . '-' . 'pattern']['option'] = $option;
                    $result[$option->getId() . '-' . 'pattern']['label'] = ucfirst('pattern');
                }

            }elseif ($option->getFrontendGroupId()=='pad'){
                $result['group-pad']['class'] = 'group-is-pad';
                $result['group-pad']['type'] = 'pad';
                $result['group-pad']['options'][] = $option;
                $result['group-pad']['label'] = ucfirst('Options');
            }else{
                $result[$option->getId() . '-' . $option->getFrontendGroupId()]['class'] = 'group-is-' . $option->getFrontendGroupId();
                $result[$option->getId() . '-' . $option->getFrontendGroupId()]['type'] = $option->getFrontendGroupId();
                $result[$option->getId() . '-' . $option->getFrontendGroupId()]['option'] = $option;
                $result[$option->getId() . '-' . $option->getFrontendGroupId()]['label'] = ucfirst($option->getTitle());
            }
        }
        return $result;
    }

    protected function _getGroupType($_option, $type){
        foreach ($_option->getValues() as $_value) {
            if($_value->getImageType()==$type){
                return true;
            }
        }
        return false;
    }

    protected function _getValueGroups($_option)
    {
        $group = array();
        foreach ($_option->getValues() as $_value) {
            if (!in_array($_value->getImageType(), $group)) {
                $group[] = $_value->getImageType();
            }
        }
        return $group;
    }

    public function getSlideJson(){
        $result = array();
        $options = $this->getOptions();
        foreach ($options as $option){
            if($option->getFrontendGroupId()=='color'){
                $groups = $this->_getValueGroups($option);
                foreach ($groups as $group){
                    $result[$option->getId() . '-' . $group] = '0';
                }
            }else{
                $result[$option->getId() . '-' . $option->getFrontendGroupId()] = '0';
            }
        }
        return Mage::helper('core')->jsonEncode($result);
    }
}