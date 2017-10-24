<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Ajaxcartpro
 * @version    3.2.7
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Ajaxcartpro_Block_Adminhtml_Promo_Edit_Tab_Action_Add
    extends AW_Ajaxcartpro_Block_Adminhtml_Promo_Edit_Tab_Action_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _getHtmlIdPrefix()
    {
        return 'add_action_';
    }

    protected function _prepareFormValues($values)
    {
        if (array_key_exists('rule_id', $values)
            && $values['type'] == AW_Ajaxcartpro_Model_Source_Promo_Rule_Type::ADD_VALUE
        ) {
            return $values;
        }
        $values['options_required_only'] = Mage::helper('ajaxcartpro/config')->getGeneralOptionsPopupDisplayWith();
        $values['popup_content'] = Mage::helper('ajaxcartpro/config')->getAddProductConfirmationContent();
        $values['show_dialog'] = Mage::helper('ajaxcartpro/config')->getAddProductConfirmationEnabled();
        $values['close_dialog_after'] = Mage::helper('ajaxcartpro/config')->getAddProductConfirmationCountdown();
        return $values;
    }

    protected function _prepareForm()
    {
        $result = parent::_prepareForm();
        $htmlIdPrefix = $this->_getHtmlIdPrefix();
        $afterElementHtml = $this->_getDefaultCheckbox(
            $htmlIdPrefix . 'options_required_only', 'options_required_only'
        );
        $fieldset = $this->getForm()->getElements()->searchById('fieldset');
        $fieldset->addField(
            'options_required_only', 'select',
            array(
                 'label'              => $this->__('Display popup for products with required options only'),
                 'name'               => 'options_required_only',
                 'options'            => array('1' => $this->__('Yes'), '0' => $this->__('No')),
                 'after_element_html' => $afterElementHtml,
            ),
            '^'
        );
        $this->getForm()->setValues(
            $this->_prepareFormValues(Mage::registry('current_acp_promo')->getData())
        );
        return $result;
    }

}