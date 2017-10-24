<?php

/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 30/06/2016
 * Time: 16:36
 */
class QSoft_ProductDesign_Block_Inspireme extends Mage_Core_Block_Template
{
    protected $_product = false;
    
    public function getProduct(){
        if (!$this->_product){
            $this->_product = Mage::registry('current_product');
        }
        return $this->_product;
    }
    
    public function getInspiremes(){
        $product = $this->getProduct();
        
        $collection = Mage::getModel('productdesign/inspireme')->getCollection();
        $collection->addFieldToFilter('product_id', $product->getId());
        return $collection;
    }

    public function getInspireMeJson(){
        $result = array();
        $inspiremes = $this->getInspiremes();
        if ($inspiremes->count()){
            foreach ($inspiremes as $key=>$inspireme){
                $result[$key]['id'] = $inspireme->getId();
                $result[$key]['group_id'] = $inspireme->getFrontendGroupId();
                $result[$key]['options'] = Mage::helper('core')->jsonDecode($inspireme->getProductOptionsJson());
            }
        }
        return Mage::helper('core')->jsonEncode($result);
    }

    public function getWishlistDataJson(){
        $wishlistBlock = $this->getChild('customer.wishlist');
        $result = array();
        foreach ($wishlistBlock->getWishlistItems() as $key=>$_item){
            $result[$key]['id'] = $_item->getId();
            $result[$key]['options'] = $_item->getOptions();
        }
        //return $result;
        return Mage::helper('core')->jsonEncode($result);
    }

    protected function _getHelper()
    {
        return Mage::helper('wishlist');
    }

    public function getWishlistItemsCount()
    {
        return $this->_getWishlist()->getItemsCount();
    }
    /**
     * Retrieve Wishlist model
     *
     * @return Mage_Wishlist_Model_Wishlist
     */
    protected function _getWishlist()
    {
        return $this->_getHelper()->getWishlist();
    }

    /**
     * Retrieve Wishlist Product Items collection
     *
     * @return Mage_Wishlist_Model_Resource_Item_Collection
     */
    public function getWishlistItems()
    {
        if (is_null($this->_collection)) {
            $this->_collection = $this->_createWishlistItemCollection();
        }

        return $this->_collection;
    }

    /**
     * Create wishlist item collection
     *
     * @return Mage_Wishlist_Model_Resource_Item_Collection
     */
    protected function _createWishlistItemCollection()
    {
        return $this->_getWishlist()->getItemCollection();
    }

    /**
     * Check is the wishlist has items
     *
     * @return bool
     */
    public function hasWishlistItems()
    {
        return $this->getWishlistItemsCount() > 0;
    }


    public function getWishlistByProduct($productId){
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        /* @var Mage_Wishlist_Model_Wishlist $wishlist */
        $wishlist = Mage::getModel('wishlist/wishlist');
        $wishlist->loadByCustomer($customerId, true);

        $read = Mage::getSingleton('core/resource')->getConnection('core/read');
        $wishlistItems = $read->fetchAll('select wishlist_item_id from wishlist_item where wishlist_id=? and product_id=? order by wishlist_item_id desc', array($wishlist->getId(), $productId));
        $datas = array();
        $i = 0;
        foreach ($wishlistItems as $item) {
            $options = $read->fetchAll('select REPLACE(wo.`code`, "option_","") as optionId, wo.`value`, po.type from wishlist_item_option as wo INNER JOIN catalog_product_option as po on REPLACE(wo.`code`, "option_","")=po.option_id where code like "option_%" and code!="option_ids" and wishlist_item_id=?', array($item['wishlist_item_id']));
            $datas[$i]['id'] = $item['wishlist_item_id'];
            $datas[$i]['datas'] = $options;
            $i++;
        }
        return $datas;
    }
}