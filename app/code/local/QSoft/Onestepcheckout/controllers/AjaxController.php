<?php

class QSoft_Onestepcheckout_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function deleteAllAction(){

        if ($ids = $this->getRequest()->getParam('ids')) {
            try {
                $itemIds = explode(',', $ids);

                $cart = Mage::getSingleton('checkout/cart');
                foreach ($itemIds as $id){
                    $cart->removeItem($id)
                        ->save();;
                }

            } catch (Exception $e) {
                Mage::log($e->getMessage());
            }

        }
        $this->_redirect('checkout/cart');
        return;
    }
}