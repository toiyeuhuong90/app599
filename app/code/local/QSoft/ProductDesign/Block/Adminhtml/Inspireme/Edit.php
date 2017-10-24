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
class QSoft_ProductDesign_Block_Adminhtml_Inspireme_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = "productdesign";
        $this->_controller = "adminhtml_inspireme";
        $this->_updateButton("save", "label", Mage::helper("productdesign")->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper("productdesign")->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label"     => Mage::helper("productdesign")->__("Save And Continue Edit"),
            "onclick"   => "saveAndContinueEdit()",
            "class"     => "save",
        ), -100);



        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
    }

    public function getHeaderText()
    {
        if( Mage::registry("inspireme_data") && Mage::registry("inspireme_data")->getId() ){

            return Mage::helper("productdesign")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("inspireme_data")->getName()));

        }
        else{

            return Mage::helper("productdesign")->__("Add Inspire Me Item");

        }
    }
}