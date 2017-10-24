<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 15/06/2016
 * Time: 09:34
 */ 
class QSoft_ProductDesign_Helper_Data extends Mage_Core_Helper_Abstract {
    protected $_imageType = array('color'=>'Color', 'pattern'=>'Pattern','pad'=>'Pad','arm_leg'=>'ARM & LEG GRIPPING');

    public function getImageType(){
        return $this->_imageType;
    }

    public function getProductGroupDesign($_product){
        if($_product->getData('product_design_type')){
            $designTypeOptionIds = explode(',', $_product->getData('product_design_type'));
            $designTypeOptionLabels = $_product->getAttributeText('product_design_type');
            $designTypes = array();
            $backgrounds = $this->getProductBackgroundDesign($_product);
            $width = Mage::getStoreConfig('productdesign/general/image_width') ? Mage::getStoreConfig('productdesign/general/image_width') : 600;
            foreach ($designTypeOptionIds as $key=>$optionId){
                $height = $width * $backgrounds[$optionId]['height'] / $backgrounds[$optionId]['width'];
                $designTypes[$key]['id'] = $optionId;
                $designTypes[$key]['name'] = $designTypeOptionLabels[$key];
                $designTypes[$key]['key'] = Mage::getSingleton('catalog/product_url')->formatUrlKey($designTypeOptionLabels[$key]);
                $designTypes[$key]['image'] = Mage::helper('productdesign/image')->getResizedImage($backgrounds[$optionId]['image'],400,400);
                $designTypes[$key]['thumbnail'] = Mage::helper('productdesign/image')->getResizedImage($backgrounds[$optionId]['image'], 80,80);
                $designTypes[$key]['width'] = 400;
                $designTypes[$key]['height'] = (string)$height;
                $designTypes[$key]['realWidth'] = $backgrounds[$optionId]['width'];
                $designTypes[$key]['realHeight'] = $backgrounds[$optionId]['height'];
                $designTypes[$key]['is_default'] = $backgrounds[$optionId]['is_default'];
                $designTypes[$key]['image_resized'] = Mage::helper('productdesign/image')->getResizedImage($backgrounds[$optionId]['image'],1000,1000);
            }
            return $designTypes;
        }
        return false;
    }
    
    public function getProductBackgroundDesign($product){
        $return = array();
        $bgCollection = Mage::getModel('productdesign/bgdesign')->getCollection();
        $bgCollection->addFieldToFilter('product_id', $product->getId());
        foreach ($bgCollection as $item){
            $return[$item->getOptionId()]['image'] = $item->getImage();
            $return[$item->getOptionId()]['width'] = $item->getWidth();
            $return[$item->getOptionId()]['height'] = $item->getHeight();
            $return[$item->getOptionId()]['is_default'] = $item->getIsDefault();
        }
        return $return;
    }
    
    public function getProductIcons($product){
        $result = array();
        $arrIcon = explode(',', $product->getProductSpecificationIcon());
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','product_specification_icon');
        $optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
            ->addFieldToFilter('attribute_id',$attributeId)
            ->setPositionOrder('desc', true)
            ->load();
        foreach ($optionCollection as $option){
            if(in_array($option->getId(), $arrIcon)){
                $result[$option->getValue()] = $option->getThumb();
            }
        }
        return $result;
    }

    public function getJsonConfigDesign($options)
    {
        $config = array();
        foreach ($options as $option) {
            /* @var $option Mage_Catalog_Model_Product_Option */
            if ($option->getGroupByType() == QSoft_ProductDesign_Model_Catalog_Product_Option::PRODUCT_DESIGN) {
                foreach ($option->getValues() as $value) {
                    /* @var $value Mage_Catalog_Model_Product_Option_Value */
                    $id = $value->getId();
                    $config[$id]['id'] = $id;
                    $config[$id]['showInGroup'] = $option->getZoomIn();
                    $config[$id]['optionId'] = $option->getId();
                    $config[$id]['isDefault'] = $value->getIsDefault();
                    $imagesDesign = Mage::helper('core')->jsonDecode($value->getDesignImages());
                    foreach ($imagesDesign as $imageDesign) {
                        $config[$id][$imageDesign['name']] = Mage::helper('productdesign/image')->getResizedImage($imageDesign['value'], 400, 400);
                    }
                }
            }

        }

        return Mage::helper('core')->jsonEncode($config);
    }

    public function getCustomerMeasureValues(){
        $result = array(
            'isLogged'=>0,
            'hasMeasure'=>0,
            'hasBodyScan'=>0
        );
        if(Mage::getSingleton('customer/session')->isLoggedIn()){
            $result['isLogged'] = 1;
            $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
            $valueCollection = Mage::getModel('qsoft_customermeasure/value')->getCollection();
            $valueCollection->addFieldToFilter('customer_id', $customerId);
            if($value = $valueCollection->getFirstItem()){
                if($value->getMeasures()){
                    $result['hasMeasure'] = 1;
                }
                if($value->getBodyScan()){
                    $result['hasBodyScan'] = 1;
                }
            }
        }
        return $result;
    }

    public function getShareImage($product, $shareId = null){
        if($shareId){
            $collection = Mage::getModel("productdesign/socialshare")->getCollection();
            $collection->addFieldToFilter('product_id',$product->getId())
                ->addFieldToFilter('share_id', $shareId);
            $shareItem = $collection->getFirstItem();
            if($shareItem->getId()){
                return $shareItem;
            }
        }
        return false;
    }

    public function hasVideos($product)
    {
        /* @var $product Mage_Catalog_Model_Product */
        if(Mage::getResourceModel('catalog/product')->getAttributeRawValue($product->getId(), 'video_360', Mage::app()->getStore()->getStoreId())){
            return true;
        }

        $options = $product->getOptions();
        foreach ($options as $option){
            foreach ($option->getValues() as $value){
                if($value->getVideo()){
                    return true;
                }
            }
        }

        return false;
    }

    public function getVideoUrl($url)
    {
        if(strpos($url,'youtube.com')){
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            $id = isset($params['v']) ? $params['v'] : 'saB-EgDcgOk';
            return 'https://www.youtube.com/embed/'. $id .'?ecver=1';
        }
        return $url;
    }
}