<?php
class QSoft_ProductDesign_Helper_Image extends Mage_Core_Helper_Abstract{

    public function getResizedImage($image, $width, $height, $quality = 100) {
        $arrPath = explode('media', $image);
        $imagePath = 'media' . $arrPath[1];
        $imageUrl = Mage::getBaseDir().DS.$imagePath;
        $imageResized = Mage::getBaseDir().DS."productDesign".DS.$width.'x'.DS.$imagePath;
        

        if (!is_file( $imageUrl ))
            return false;


        if (!file_exists($imageResized) && file_exists($imageUrl) || file_exists($imageUrl) && filemtime($imageUrl) > filemtime($imageResized)):
            $imageObj = new Varien_Image($imageUrl);
            $imageObj->constrainOnly(true);
            $imageObj->keepAspectRatio(true);
            $imageObj->keepFrame(true);
            $imageObj->backgroundColor(array(255, 255, 255, 0));
	        $imageObj->keepTransparency(true);
            $imageObj->resize($width, $height);
            $imageObj->save($imageResized);
        endif;

        if(file_exists($imageResized)){
            $imageResized = str_replace(array(Mage::getBaseDir().DS,DS), array(Mage::getBaseUrl(), '/'),$imageResized);
            if(strpos(Mage::getBaseUrl('media'),'http')){
                $imageResized = str_replace(Mage::getBaseUrl().'media',Mage::getBaseUrl('media'),$imageResized);
            }
            $imageResized = str_replace('index.php/', '', $imageResized);
            if(Mage::app()->getStore()->isCurrentlySecure()) {
                $imageResized = str_replace('http://', 'https://', $imageResized);
            }
            return $imageResized;
        }
        else{
            return $imageResized;
        }
    }

    public function resizedShareImage($imageResized,$imageUrl, $width, $height = null, $quality = 100) {
        if (!$imageUrl)
            return false;

        if (!file_exists($imageResized) && file_exists($imageUrl) || file_exists($imageUrl) && filemtime($imageUrl) > filemtime($imageResized)):
            $imageObj = new Varien_Image($imageUrl);
            $imageObj->constrainOnly(true);
            $imageObj->keepAspectRatio(true);
            $imageObj->keepFrame(true);
            $imageObj->backgroundColor(array(255, 255, 255));
            $imageObj->keepTransparency(true);
            $imageObj->resize($width, $height);
            $imageObj->save($imageResized);
        endif;

        if(file_exists($imageResized)){
            $imageResized = str_replace(array(Mage::getBaseDir().DS,DS), array(Mage::getBaseUrl(), '/'),$imageResized);
            if(strpos(Mage::getBaseUrl('media'),'http')){
                $imageResized = str_replace(Mage::getBaseUrl().'media',Mage::getBaseUrl('media'),$imageResized);
            }
            if(Mage::app()->getStore()->isCurrentlySecure()) {
                $imageResized = str_replace('http://', 'https://', $imageResized);
            }
            return $imageResized;
        }
        else{
            return Mage::getDesign()->getSkinUrl().'images/category_image.jpg';
        }
    }
}
