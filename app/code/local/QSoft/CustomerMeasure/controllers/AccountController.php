<?php
class QSoft_CustomerMeasure_AccountController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }
    
    public function indexAction(){
        $this->loadLayout();
        $this->_title($this->__('My Measurements'));
        $this->renderLayout();
    }
    public function updatePostAction(){
        $this->_saveCustomerMeasure();
        $this->_redirect("customer/account/");
    }

    public function ajaxUpdateAction(){
        $this->_saveCustomerMeasure();
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('status'=>true)));
    }

    protected function _saveCustomerMeasure(){
        if($postData = $this->getRequest()->getPost()){
            $measurePostData = $postData['measure'];
            $dataSave = array();
            $valueCollection = Mage::getModel('qsoft_customermeasure/value')->getCollection();
            $valueCollection->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomerId());
            if($valueCollection->getFirstItem()->getId()){
                $id = $valueCollection->getFirstItem()->getId();
            }else{
                $id = null;

            }

            $model = Mage::getModel('qsoft_customermeasure/value');
            $model->load($id);

            $dataSave['measures'] = Mage::helper('core')->jsonEncode($measurePostData['item']);
            $dataSave['unit'] = Mage::helper('core')->jsonEncode($measurePostData['unit']);
            $dataSave['customer_id'] = Mage::getSingleton('customer/session')->getCustomerId();
            $dataSave['updated_at'] = Varien_Date::now();

            $model->addData($dataSave)
                ->save();

        }
    }
}