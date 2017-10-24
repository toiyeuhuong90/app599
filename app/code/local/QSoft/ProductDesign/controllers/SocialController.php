<?php
class QSoft_ProductDesign_SocialController extends Mage_Core_Controller_Front_Action
{
    public function shareAction(){
        if($postData=$this->getRequest()->getPost()){
            $shareId = Mage::helper('core')->getRandomString(20);
            $product = Mage::getModel('catalog/product')->load($postData['product']);
            $baseDir = Mage::getBaseDir().DS."media".DS."productDesign".DS.'shared';
            $imageName = time().'.png';
            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0755);
            }
            $dirImage = $baseDir.DS.'tmp.png';
            $img = str_replace('data:image/png;base64,', '', $postData['base64Image']);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            file_put_contents($dirImage, $data);
            $image = Mage::helper('productdesign/image')->resizedShareImage($baseDir.DS.$imageName, $dirImage, 1200, 630);
            unlink($dirImage);
            $model = Mage::getModel("productdesign/socialshare");
            $dataSave = array(
                'share_id'        => $shareId,
                'product_id'      => $product->getId(),
                'product_options' => Mage::helper('core')->jsonEncode($postData['option']),
                'image'           => $image,
                'created_at'      => Mage::getModel('core/date')->date('Y-m-d H:i:s')
            );

            $model->addData($dataSave);
            $model->save();
            $urlShare = Mage::helper('core/url')->addRequestParam($product->getProductUrl(), array('share'=>$shareId));
	    $url = 'http://www.facebook.com/sharer.php?u='.urlencode($urlShare).'&picture='.urlencode($image);
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('url'=>$url)));
        }
    }
}
