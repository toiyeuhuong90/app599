<?php

class QSoft_ProductDesign_Block_Catalog_Product_View_Options_Type_Designimage extends Mage_Catalog_Block_Product_View_Options_Abstract
{
    public function getValuesHtml($displayType)
    {
        $_option = $this->getOption();
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();
        $selectHtml = '';
        $require = ($_option->getIsRequire()) ? ' validate-one-required-by-name' : '';
        $arraySign = '';

        $type = 'radio';
        $class = 'radio';


        foreach ($_option->getValues() as $_value) {
            if ($_value->getImageType() == $displayType) {
                $priceStr = $this->_formatPrice(array(
                    'is_percent' => ($_value->getPriceType() == 'percent'),
                    'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent')
                ));

                $htmlValue = $_value->getOptionTypeId();
                if ($arraySign) {
                    $checked = (is_array($configValue) && in_array($htmlValue, $configValue)) ? 'checked' : '';
                } else {
                    $checked = $configValue == $htmlValue ? 'checked' : '';
                }
                $spanClass = '';
                if ($_value->getIsDefault()) {
                    $checked = 'checked is_default="1" optionselected="'.$_option->getId().'" ';
                    $spanClass = 'active';
                }

                $selectHtml .= '<div class="swiper-slide qs-col-choose"><div class="qs-choose-wrapper" data-parent="tab-panel-' . $_option->getId() . '">' . '<input type="' . $type . '" class="option_image_design ' . $class . ' ' . $require
                    . ' product-custom-option"'
                    . ($this->getSkipJsReloadPrice() ? '' : ' onclick="updateColorActive(\'span-color-'.$_value->getId().'\');design.buildDesign(this); opConfig.reloadPrice(); updateTabInfo(\''.$_option->getId().'\',\''.ucfirst($displayType).'\',\'price'.$_value->getId().'\',\''.$this->escapeHtml($_value->getTitle()).'\'); "')
                    . ' name="options[' . $_option->getId() . ']' . $arraySign . '" optionid="' . $_option->getId() .'" id="options_' . $_option->getId()
                    . '_' . $_value->getId() . '" value="' . $htmlValue . '" ' . $checked . ' price="'
                    . $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false) . '" run-on-start=" updateTabInfo(\''.$_option->getId().'\',\''.ucfirst($displayType).'\',\'price'.$_value->getId().'\',\''.$this->escapeHtml($_value->getTitle()).'\');"/>'
                    . '<span id="span-color-'.$_value->getId().'" valueid="'.$_value->getId().'" for="options_' . $_option->getId() . '_' . $_value->getId() . '" ' . $checked . ' optionid="' . $_option->getId() .'" class="qs-color-design '.$spanClass.'"><label for="options_' . $_option->getId() . '_' . $_value->getId() . '">'
                    . '<img alt="' . $this->escapeHtml($_value->getTitle()) . '" src="' . $_value->getIcon() . '"/></label></span><div id="price'.$_value->getId().'" style="display:none;">' . $priceStr . '</div>';
                $selectHtml .= '</div></div>';

            }

        }

        return $selectHtml;
    }
}