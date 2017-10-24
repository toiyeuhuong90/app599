<?php

class QSoft_ProductDesign_Adminhtml_AjaxController extends Mage_Adminhtml_Controller_Action
{
    protected $_smGroup = array();
    protected $_stores = array();

    public function bgdesignAction(){
        $options = Mage::helper('core')->jsonDecode($this->getRequest()->getPost('data'));
        if($productId=$this->getRequest()->getParam('id')){
            $bgCollection = Mage::getModel('productdesign/bgdesign')->getCollection()
                ->addFieldToFilter('product_id', $productId);
            foreach ($options as $key=>$option){
                foreach ($bgCollection as $item){
                    if($item->getOptionId()==$option['id']){
                        $options[$key]['image'] = $item->getImage();
                        $options[$key]['width'] = $item->getWidth();
                        $options[$key]['height'] = $item->getHeight();
                        $options[$key]['is_default'] = $item->getIsDefault();
                        break;
                    }
                }
            }
        }
        $block = $this->getLayout()->createBlock('core/template')
            ->setTemplate('qsoft/productdesign/ajax/bgimages.phtml');
        $block->setOptions($options);
        $content['content'] = $block->toHtml();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($content));
    }

    public function importProductOptionsAction()
    {
        $productId = $this->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')->load($productId);
        $inspireme = array();
        foreach($product->getOptions() as $option){
            $inspireme[$option->getId()] = '';
        }

        $frontendGroupId = 0;
        if($inspiremeId = $this->getRequest()->getParam('inspiremeId')){
            $model = Mage::getModel('productdesign/inspireme')->load($inspiremeId);
            $inspireme = Mage::helper('core')->jsonDecode($model->getProductOptionsJson());
            $frontendGroupId = $model->getFrontendGroupId();
        }
        $blockContent = $this->getLayout()->createBlock('core/template')
            ->setTemplate('qsoft/productdesign/inspireme/product.options.phtml')
            ->setInspireme($inspireme)
            ->setfrontendGroupId($frontendGroupId)
            ->setOptions($product->getOptions())
            ->setProduct($product);

        //var_dump($inspireme);
        $blockJs = $this->getLayout()->createBlock('core/template')
            ->setTemplate('qsoft/productdesign/inspireme/product.js.phtml')
            ->setInspireme($inspireme)
            ->setOptions($product->getOptions())
            ->setProduct($product);

//        $opConfigDesign = Mage::helper('productdesign')->getJsonConfigDesign($product->getOptions());
//        $groupsDesign = Mage::helper('productdesign')->getProductGroupDesign($product);
//        $opGroupDesign = Mage::helper('core')->jsonEncode($groupsDesign);

        $content['content'] = $blockContent->toHtml();
        $content['js'] = $blockJs->toHtml();
//        $content['opConfigDesign'] = $opConfigDesign;
//        $content['opGroupDesign'] = $opGroupDesign;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($content));
    }
}