<?php

/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 30/06/2016
 * Time: 16:36
 */
class QSoft_ProductDesign_Block_Catalog_Product_View_Options extends Mage_Catalog_Block_Product_View_Options
{

    public function getRelated(){
        $product = Mage::registry('current_product');
        $_itemCollection = $product->getRelatedProductCollection()
            ->addAttributeToSelect('required_options')
            ->setPositionOrder()
            ->addStoreFilter()
        ;

        if (Mage::helper('catalog')->isModuleEnabled('Mage_Checkout')) {
            Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($_itemCollection,
                Mage::getSingleton('checkout/session')->getQuoteId()
            );
            $_itemCollection->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addUrlRewrite();
        }
//        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_itemCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($_itemCollection);

        $_itemCollection->load();

        foreach ($_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $_itemCollection;
    }
    
    public function getJsonTextDesign()
    {
        $config = array();
        foreach ($this->getOptions() as $option) {
            /* @var $option Mage_Catalog_Model_Product_Option */
            if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_TEXT) {
                $zoomIn = explode(',', $option->getZoomIn());
                $id = $option->getId();;
                $config[$id]['id'] = $id;
                $config[$id]['top'] = $option->getCoordinateTop();
                $config[$id]['left'] = $option->getCoordinateLeft();
                $config[$id]['groupId'] = $zoomIn[0];
            }

        }

        return Mage::helper('core')->jsonEncode($config);
    }

    public function getColorForTextDesign()
    {
        $givingNameGroup = explode(',', Mage::getStoreConfig('productdesign/group_type/giving_name'));
        $config = '';
        foreach ($this->getOptions() as $option) {
            /* @var $option Mage_Catalog_Model_Product_Option */
            if (in_array($option->getFrontendGroupId(), $givingNameGroup) && $option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
                if($config==''){
                    $config = $option->getId();
                }else{
                    $config .= ',' . $option->getId();
                }
            }

        }

        return $config;
    }


    public function getJsonConfigDesign()
    {
        $config = array();
        foreach ($this->getOptions() as $option) {
            /* @var $option Mage_Catalog_Model_Product_Option */
            if ($option->getGroupByType() == QSoft_ProductDesign_Model_Catalog_Product_Option::PRODUCT_DESIGN) {
                foreach ($option->getValues() as $value) {
                    /* @var $value Mage_Catalog_Model_Product_Option_Value */
                    $id = $value->getId();
                    $config[$id]['id'] = $id;
                    $config[$id]['showInGroup'] = $option->getZoomIn();
                    $config[$id]['optionId'] = $option->getId();
                    $config[$id]['isDefault'] = $value->getIsDefault();
                    $imagesDesign = $this->helper('core')->jsonDecode($value->getDesignImages());
                    $k=1;
                    foreach ($imagesDesign as $imageDesign) {
                        $img = false;
                        if($imageDesign['value']){
                            $arrPath = explode('media', $imageDesign['value']);
                            $img = str_replace('index.php/', '', $this->getUrl() . 'media/' . $arrPath[1]);
                        }

                        if($img){
                            $config[$id][$k.'-image'] = Mage::helper('productdesign/image')->getResizedImage($img, 400, 400);
                            $config[$id][$k.'-zoom-image'] = Mage::helper('productdesign/image')->getResizedImage($img, 1000, 1000);
                        }

                        $k++;
                        //$config[$id][$imageDesign['name']] = Mage::helper('productdesign/image')->getResizedImage($imageDesign['value']);
                    }
                }
            }

        }

        return Mage::helper('core')->jsonEncode($config);
    }

    /**
     * Get json representation of
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $config = array();

        foreach ($this->getOptions() as $option) {
            /* @var $option Mage_Catalog_Model_Product_Option */
            $priceValue = 0;
            if (in_array($option->getGroupByType(), array(Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT, QSoft_ProductDesign_Model_Catalog_Product_Option::PRODUCT_DESIGN))) {
                $_tmpPriceValues = array();
                foreach ($option->getValues() as $value) {
                    /* @var $value Mage_Catalog_Model_Product_Option_Value */
                    $id = $value->getId();
                    $_tmpPriceValues[$id] = $this->_getPriceConfiguration($value);
                }
                $priceValue = $_tmpPriceValues;
            } else {
                $priceValue = $this->_getPriceConfiguration($option);
            }
            $config[$option->getId()] = $priceValue;
        }

        return Mage::helper('core')->jsonEncode($config);
    }

    public function getOptionTabMobileContent($optionTab){
        $html = '<div class="qs-tab-pane color-parent-mobile-pane"  id="option-tab-mobile-color">';
        $html.= '<ul class="list-group-pane list-mobile-color-item">';
        foreach ($optionTab['groups'] as $group){
            $html.= '<li>';
            $html.= '<a class="item" title="'.ucfirst($group).'" href="javascript:void(0);" onclick="goToMobileColorItem(\'child-'.$optionTab['option']->getId().'-'.$group.'\', this);">'.ucfirst($group).'</a>';
            $html.= '</li>';
        }
        $html.= '</ul>';
        $html.= '</div>';
        return $html;
    }

    public function getOptionTabContent($id, $optionsTab, $sttClass)
    {
        switch ($optionsTab['type']) {
            case 'color':
                return $this->getColorGroup($id, $optionsTab, $sttClass);
                break;
            case 'size':
                return $this->getSizeGroup($id, $optionsTab, $sttClass);
                break;
            case 'pad':
                return $this->getPadGroup($id, $optionsTab, $sttClass);
                break;
            case 'multi';
                return $this->getProductGroup($id,$optionsTab, $sttClass);
                break;
            default:
                return $this->getGroupHtml($optionsTab, $sttClass);
        }
    }

    public function getProductGroup($id, $optionsTab, $sttClass){
        $html = '<div class="qs-tab-pane product-pane '.$sttClass.'" id="option-tab-' . $id . '">';
        $html.= $this->getOptionTypeHtml($optionsTab['option'], 'multi');
        $html.= '</div>';
        return $html;
    }

    public function getPadGroup($id, $optionsTab, $sttClass){
        $html = '<div class="qs-tab-pane option-pad-pane '.$sttClass.'" id="option-tab-' . $id . '">';
        $html.= '<ul class="list-group-pane list-pad">';
        foreach ($optionsTab['options'] as $option){
            $html.= '<li>';
            $html.= '<a class="item" title="'.$option->getTitle().'" href="javascript:void(0);" onclick="showPadDetail(\''.$option->getId().'-pad\', this);">'.$option->getTitle().'</a>';
            $html.= '</li>';
        }
        $html.= '</ul>';
        foreach ($optionsTab['options'] as $option){
            $html.= $this->getOptionTypeHtml($option, 'pad');
        }
        $html.= '</div>';
        return $html;
    }

    protected function getColorGroup($id, $optionsTab, $sttClass)
    {
        $html = '<div class="qs-tab-pane color-pane '.$sttClass.'" id="option-tab-' . $id . '">';
        $html.= $this->getOptionTypeHtml($optionsTab['option'], $optionsTab['group']);
        //end panel
        $html .= '</div>';
        return $html;
    }
    
    protected function getGivingNameGroup($optionsTab, $sttClass){
        $html = '<div class="qs-tab-pane option-giving-pane '.$sttClass.'" id="option-tab-' . $optionsTab['group']['id'] . '">';
        $html .= '<div class="qs-name-tab-control">Engraving Name</div>';
        $html .= '<div class="qs-tab-control-wrapper qs-tab-engravingname-panel">';
        foreach ($optionsTab['options'] as $_option) {
            $html.= $this->getOptionTypeHtml($_option, $optionsTab['options']);
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    protected function getSizeGroup($id, $optionsTab, $sttClass){
        $html = '<div class="qs-tab-pane size-pane '.$sttClass.'" id="option-tab-' . $id . '">';

        $additional = '
        <div class="swiper-slide qs-col-nopading">
        <input onclick="getCustomerMeasure(this,'.$optionsTab['option']->getId().');" id="measurement" type="radio" name="measurement" value="measurement" />
            <div class="qs-measurement">
                <div class="qs-row-choose-size">
                    <label for="measurement">
                        
                        <span class="bx-img"><img src="'.$this->getSkinUrl('images/1-grey.png').'" title="Measurement" alt="Measurement"/></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="swiper-slide qs-col-nopading">
            <div class="qs-measurement">
            <input onclick="getCustomerBodyscan(this, '.$optionsTab['option']->getId().');" id="bodyscan" type="radio" name="measurement" value="bodyscan" />
                <div class="qs-row-choose-size">
                    <label for="bodyscan">
                        
                        <span class="bx-img"><img src="'.$this->getSkinUrl('images/2-grey.png').'" title="Body scan" alt="Body scan"/></span>
                    </label>
                </div>
            </div>
        </div>
        ';

        $html.= $this->getOptionTypeHtml($optionsTab['option'], 'size', $additional);

        $html .= '</div>';
        return $html;
    }

    protected function getGroupHtml($optionsTab, $sttClass){
        $html = '<div class="qs-tab-pane option-pane '.$sttClass.'" id="option-tab-' . $optionsTab['group']['id'] . '">';
        $html .= '<div class="qs-name-tab-control">'.$optionsTab['group']['name'].'</div>';
        $html .= '<div class="qs-tab-control-wrapper">';
        foreach ($optionsTab['options'] as $_option) {
            $html.= $this->getOptionHtml($_option);
        }

        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Get option html block
     *
     * @param Mage_Catalog_Model_Product_Option $option
     */
    public function getOptionTypeHtml(Mage_Catalog_Model_Product_Option $option, $group, $additional = '')
    {
        $renderer = $this->getOptionRender(
            $this->getGroupOfOption($option->getType())
        );
        if (is_null($renderer['renderer'])) {
            $renderer['renderer'] = $this->getLayout()->createBlock($renderer['block'])
                ->setTemplate($renderer['template']);
        }
        return $renderer['renderer']
            ->setProduct($this->getProduct())
            ->setOption($option)
            ->setGroupType($group)
            ->setAdditional($additional)
            ->toHtml();
    }
}