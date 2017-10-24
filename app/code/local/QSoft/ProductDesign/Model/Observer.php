<?php
class QSoft_ProductDesign_Model_Observer{
    public function saveBackgroundDesign($observer){
        $product = $observer->getProduct();
        if($productId=$product->getId()){
            $bgCollection = Mage::getModel('productdesign/bgdesign')->getCollection()
                ->addFieldToFilter('product_id', $productId);
            if($bgCollection->count()){
                foreach($bgCollection as $item){
                    $item->delete();
                }
            }
            if($bgPost = Mage::app()->getRequest()->getPost('bg')){
                $dataInsert['is_default'] = 0;
                foreach ($bgPost as $optionId=>$image){
                    $model = Mage::getModel('productdesign/bgdesign');
                    $dataInsert = array();
                    $dataInsert['product_id'] = $productId;
                    $dataInsert['option_id'] = $optionId;
                    $dataInsert['image'] = $image['image'];
                    $dataInsert['width'] = $image['width'];
                    $dataInsert['height'] = $image['height'];
                    if($image['is_default']){
                        $dataInsert['is_default'] = 1;
                    }

                    $model->setData($dataInsert)
                        ->save();
                }

            }
        }
    }

    public function checkoutCartProductAddAfter($observer){
        $quoteItem = $observer->getQuoteItem();
        if($dataImage = Mage::app()->getRequest()->getParam('design_image')){

            $quoteItem->setImageDesign($dataImage);
            Mage::register('quote_item_added', $dataImage);

        }

        // when wishlist add to cart

        if($itemId = Mage::app()->getRequest()->getParam('item')){
            $wishlistItem = Mage::getModel('wishlist/item')->load($itemId);
            $quoteItem->setImageDesign($wishlistItem->getImageDesign());
        }

        $action = Mage::app()->getFrontController()->getAction();
        if ($action->getFullActionName() == 'checkout_cart_add')
        {
            // assuming you are posting your custom form values in an array called extra_options...
            if ($options = $action->getRequest()->getParam('measurement'))
            {
                $product = $observer->getProduct();

                // add to the additional options array
                $additionalOptions = array();
                if ($additionalOption = $product->getCustomOption('additional_options'))
                {
                    $additionalOptions = (array) unserialize($additionalOption->getValue());
                }

                    $additionalOptions[] = array(
                        'label' => 'Size',
                        'value' => $options,
                    );

                // add the additional options array with the option code additional_options
                $observer->getProduct()
                    ->addCustomOption('additional_options', serialize($additionalOptions));
                $quoteItem->addOption(array(
                    'code' => 'additional_options',
                    'value' => serialize($additionalOptions)
                ));
            }
        }
        if ($action->getFullActionName() == 'sales_order_reorder')
        {


            $buyInfo = $quoteItem->getBuyRequest();
            if ($options = $buyInfo->getExtraOptions())
            {
                $additionalOptions = array();
                if ($additionalOption = $quoteItem->getOptionByCode('additional_options'))
                {
                    $additionalOptions = (array) unserialize($additionalOption->getValue());
                }
                foreach ($options as $key => $value)
                {
                    $additionalOptions[] = array(
                        'label' => $key,
                        'value' => $value,
                    );
                }
                $quoteItem->addOption(array(
                    'code' => 'additional_options',
                    'value' => serialize($additionalOptions)
                ));
            }
        }

        //return $this;
    }

    public function wishlistProductAddAfter($observer){
        $wishlistItems = $observer->getItems();
        foreach ($wishlistItems as $item){

            if($dataImage = Mage::app()->getRequest()->getParam('design_image')){
                $item->setImageDesign($dataImage);
                $item->save();
            }
        }


        //return $this;
    }

    public function salesConvertQuoteItemToOrderItem(Varien_Event_Observer $observer){
        $quoteItem = $observer->getItem();
        if ($additionalOptions = $quoteItem->getOptionByCode('additional_options')) {
            $orderItem = $observer->getOrderItem();
            $options = $orderItem->getProductOptions();
            $options['additional_options'] = unserialize($additionalOptions->getValue());
            $orderItem->setProductOptions($options);
        }
    }
}