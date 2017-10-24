<?php

/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 12/08/2016
 * Time: 15:17
 */
class QSoft_ProductDesign_Block_Catalog_Product_View_Options_Type_Select extends Mage_Catalog_Block_Product_View_Options_Type_Select
{
    /**
     * Return html for control element
     *
     * @return string
     */
    public function getGivingNameValuesHtml()
    {
        $_option = $this->getOption();
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();
        $type = 'radio';
        $class = 'radio';
        $selectHtml = '';
        $require = ($_option->getIsRequire()) ? ' validate-one-required-by-name' : '';
        $arraySign = '';

        $count = 1;
        foreach ($_option->getValues() as $_value) {
            $count++;

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
            $label = $this->escapeHtml($_value->getTitle()) . ' ' . $priceStr;
            if ($color = $_value->getColorCode()) {
                $label = '<span style="background: #' . $color . ' none;" title="' . $this->escapeHtml($_value->getTitle()) . '"></span>';
            }
            $spanClass = '';
            if ($_value->getIsDefault()) {
                $checked = 'checked is_default="1" ';
                $spanClass = 'active';
            }
            $selectHtml .= '<li class="qs-col-choose-color">'
                . '<div class="qs-choose-color-wrapper">'
                . '<input color_code="'.$_value->getColorCode().'" type="' . $type . '" class="' . $class . ' ' . $require
                . ' product-custom-option color-for-giving-text"'
                . ($this->getSkipJsReloadPrice() ? '' : ' onclick="updateTabInfo(\''.$_option->getId().'\',\'color\',\'price'.$_value->getId().'\',\''.$this->escapeHtml($_value->getTitle()).'\');opConfig.reloadPrice(); design.buildDesign();"')
                . ' name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId()
                . '_' . $_value->getId() . '" value="' . $htmlValue . '" ' . $checked . ' price="'
                . $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false) . '" run-on-start=" updateTabInfo(\'' . $_option->getId() . '\',\'color\',\'price' . $_value->getId() . '\',\'' . $this->escapeHtml($_value->getTitle()) . '\');"/>'
                . '<span class="span-color-giving-text ' . $spanClass . '"><label for="options_' . $_option->getId() . '_' . $_value->getId() . '">' . $label
                . '</label></span>'
                . '<div id="price' . $_value->getId() . '" style="display:none;">' . $priceStr . '</div>';

            $selectHtml .= '</div></li>';
        }


        return $selectHtml;

    }

    /**
     * Return html for control element
     *
     * @return string
     */
    public function getSizeValuesHtml()
    {
        $_option = $this->getOption();
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();
        $type = 'radio';
        $class = 'radio';
        $selectHtml = '';
        $require = ($_option->getIsRequire()) ? ' validate-one-required-by-name' : '';
        $arraySign = '';

        $count = 1;
        foreach ($_option->getValues() as $_value) {
            $count++;

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


            if ($_value->getIsDefault()) {
                $checked = 'checked is_default="1" ';
            }
            $selectHtml .= '<div class="swiper-slide qs-row-choose-size">'
                . '<input type="' . $type . '" class="body-size ' . $class . ' ' . $require
                . ' product-custom-option"'
                . ($this->getSkipJsReloadPrice() ? '' : ' onclick="uncheckMeasurement();opConfig.reloadPrice(); updateTabInfo(\''.$_option->getId().'\',\'\',\'price'.$_value->getId().'\',\''.$this->escapeHtml($_value->getTitle()).'\');"')
                . ' name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId()
                . '_' . $_value->getId() . '" value="' . $htmlValue . '" ' . $checked . ' price="'
                . $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false) . '" />'
                . '<label for="options_' . $_option->getId() . '_' . $_value->getId() . '">'
                . '<span class="bx-text">'
                . $this->escapeHtml($_value->getTitle()) . ' ' . $priceStr . '</span></label>';

            $selectHtml .= '</div>';
        }

        $selectHtml.= $this->getAdditional();

        return $selectHtml;

    }

    public function getPadValuesHtml()
    {
        $_option = $this->getOption();
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();
        $type = 'radio';
        $class = 'radio';
        $selectHtml = '';
        $require = ($_option->getIsRequire()) ? ' validate-one-required-by-name' : '';
        $arraySign = '';

        $count = 1;
        foreach ($_option->getValues() as $_value) {
            $count++;

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


            if ($_value->getIsDefault()) {
                $checked = 'checked is_default="1" ';
            }
            $selectHtml .= '<div class="swiper-slide qs-row-choose-size">'
                . '<input type="' . $type . '" class="body-size ' . $class . ' ' . $require
                . ' product-custom-option"'
                . ($this->getSkipJsReloadPrice() ? '' : ' onclick="activePadValue(this);opConfig.reloadPrice(); updateTabInfo(\''.$_option->getId().'\',\'color\',\'price'.$_value->getId().'\',\''.$this->escapeHtml($_value->getTitle()).'\');"')
                . ' name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId()
                . '_' . $_value->getId() . '" value="' . $htmlValue . '" ' . $checked . ' price="'
                . $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false) . '" />'
                . '<label for="options_' . $_option->getId() . '_' . $_value->getId() . '">'
                . '<img alt="' . $this->escapeHtml($_value->getTitle()) . '" src="' . $_value->getIcon() . '"/>'
                . '<div class="show-hover">'
                . '<img alt="' . $this->escapeHtml($_value->getTitle()) . '" src="' . $_value->getIcon() . '"/>'
                . '<span class="title-text">'. $this->escapeHtml($_value->getTitle()) . ' ' . $priceStr . '</span></div></label>';

            $selectHtml .= '</div>';
        }

        $selectHtml.= $this->getAdditional();

        return $selectHtml;

    }

    public function getMultiValuesHtml()
    {
        $_option = $this->getOption();
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();
        $type = 'checkbox';
        $class = 'checkbox';
        $selectHtml = '';
        $require = ($_option->getIsRequire()) ? ' validate-one-required-by-name' : '';
        $arraySign = '';

        $count = 1;
        foreach ($_option->getValues() as $_value) {
            $count++;

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


            if ($_value->getIsDefault()) {
                $checked = 'checked is_default="1" ';
            }
            $selectHtml .= '<div class="swiper-slide qs-row-choose-multi">'
                . '<input type="' . $type . '" class="op-product-'. $_option->getId() .' body-size ' . $class . ' ' . $require
                . ' product-custom-option"'
                . ($this->getSkipJsReloadPrice() ? '' : ' onclick="activeProductValue(this);opConfig.reloadPrice(); updateTabInfo(\''.$_option->getId().'\',\'product\',\'price'.$_value->getId().'\',\''.$this->escapeHtml($_value->getTitle()).'\');"')
                . ' title="'.$this->escapeHtml($_value->getTitle()).'" name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId()
                . '_' . $_value->getId() . '" value="' . $htmlValue . '" ' . $checked . ' price="'
                . $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false) . '" />'
                . '<label for="options_' . $_option->getId() . '_' . $_value->getId() . '">'
                . '<img alt="' . $this->escapeHtml($_value->getTitle()) . '" src="' . $_value->getIcon() . '"/>'
                . '<div class="show-hover">'
                . '<span class="title-text">'. $this->escapeHtml($_value->getTitle()) . ' ' . $priceStr . '</span>'
                . '</div>'
                . '</label>';

            $selectHtml .= '</div>';
        }

        $selectHtml.= $this->getAdditional();

        return $selectHtml;

    }

}