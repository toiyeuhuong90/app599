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
class QSoft_ProductDesign_Block_Adminhtml_Inspireme_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('productoptions_form', array('legend' => Mage::helper("productdesign")->__('Item information')));


        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper("productdesign")->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',

        ));


        $object = Mage::registry('inspireme_data');
        if(isset($object) && $object->getProductId()){
            $product = $fieldset->addField('note', 'note', array(
                'label' => Mage::helper("productdesign")->__('Product'),
                'text'     => $this->getProductName($object->getProductId()),
            ));
            $product->setAfterElementHtml("<script type=\"text/javascript\">
            Event.observe(window, 'load', function () {
                var reloadUrl = '". $this->getUrl('qsproductdesign/adminhtml_ajax/importProductOptions',array('id'=>$object->getProductId(), 'inspiremeId'=>$object->getId())) . "';
                new Ajax.Request(reloadUrl, {
                    type: 'post',
                    beforeSend: function () {
                    },
                    onSuccess: function(response) {
                        var json = response.responseText.evalJSON(true);
                        document.getElementById('product_options').innerHTML = json.content;
                        setTimeout(function(){
                            ajaxCallBack(json.js);                            
                        },200);
                    }
                });
            });
        </script>");
        }else{
            $product = $fieldset->addField('product_id', 'select', array(
                'label' => Mage::helper("productdesign")->__('Product'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'product_id',
                'onchange' => 'getProductOptions(this)',
                'values' => $this->getInspireMeProduct()
            ));
            $product->setAfterElementHtml("<script type=\"text/javascript\">
            function getProductOptions(selectElement){
                var reloadUrl = '". $this->getUrl('qsproductdesign/adminhtml_ajax/importProductOptions') . "id/' + selectElement.value;
                new Ajax.Request(reloadUrl, {
                    type: 'post',
                    beforeSend: function () {
                    },
                    onSuccess: function(response) {
                        var json = response.responseText.evalJSON(true);
                        document.getElementById('product_options').innerHTML = json.content;
                        setTimeout(function(){
                            ajaxCallBack(json.js);                            
                        },200);
                    }
                });
            }
        </script>");
        }


        $fieldset->addField('description', 'textarea', array(
            'label' => Mage::helper("productdesign")->__('Description'),
            'name' => 'description',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label' => Mage::helper("productdesign")->__('Sort Order'),
            'name' => 'sort_order',
        ));


        $fieldset2 = $form->addFieldset('product_options', array('legend' => Mage::helper("productdesign")->__('Product Customs Options')));

        /*
         * Add Ajax to the product select box html output
         */


        if (Mage::getSingleton('adminhtml/session')->getInspiremeData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getInspiremeData());
            Mage::getSingleton('adminhtml/session')->setInspiremeData(null);
        } elseif (Mage::registry('inspireme_data')) {
            $form->setValues(Mage::registry('inspireme_data')->getData());
        }

        return parent::_prepareForm();
    }



    public function getInspireMeProduct()
    {
        $collection = Mage::getModel('productdesign/inspireme')->getInspireMeProducts();

        $products = array(
            array(
                'value' => -1,
                'label' => Mage::helper("productdesign")->__('Please Select'),
            ));

        foreach ($collection as $product) {
            $products[] = array(
                'label' => $product->getName(),
                'value' => $product->getId()
            );
        }

        return $products;
    }

    protected function getProductName($productid){
        $product = Mage::getModel('catalog/product')->load($productid);
        return $product->getName();
    }
}