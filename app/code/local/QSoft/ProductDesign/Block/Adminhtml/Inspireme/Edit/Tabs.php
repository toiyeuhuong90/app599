<?php

/**
 * QSoft Vietnam
 * http://www.qsoftvietnam.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@qsoftvietnam.com so we can send you a copy immediately.
 *
 * @category    QSoft
 * @package     QSoft_ProductDesign
 * @author      Tuyen Nguyen <tuyennn@qsoftvietnam.com>
 * @copyright   Copyright (c) 2016 (http://www.qsoftvietnam.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class QSoft_ProductDesign_Block_Adminhtml_Inspireme_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('inspireme_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper("productdesign")->__('Item Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper("productdesign")->__('Inspire Me Information'),
            'title' => Mage::helper("productdesign")->__('Inspire Me Information'),
            'content' => $this->getLayout()->createBlock('productdesign/adminhtml_inspireme_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}