<?php
class QSoft_Customer_Adminhtml_SchedulebodyscanController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("customer")->_addBreadcrumb(Mage::helper("adminhtml")->__("Schedule fitting  Manager"),Mage::helper("adminhtml")->__("Group Manager"));
        return $this;
    }
    public function indexAction(){
        $this->_title($this->__("Schedule fitting"));
        $this->_title($this->__("Manager Schedule fitting"));

        $this->_initAction();
        $this->renderLayout();
    }


    public function editAction()
    {
        $this->_title($this->__("Schedule fitting"));
        $this->_title($this->__("Schedule"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("qsoft_customer/schedulebodyscan")->load($id);
        if ($model->getId()) {
            Mage::register("group_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("customer");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Schedule fitting Manager"), Mage::helper("adminhtml")->__("Schedule fitting Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Schedule fitting Description"), Mage::helper("adminhtml")->__("Schedule fitting Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("qsoft_customer/adminhtml_schedulebodyscan_edit"))->_addLeft($this->getLayout()->createBlock("qsoft_customer/adminhtml_schedulebodyscan_edit_tabs"));
            $this->renderLayout();
        }
        else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("qsoft_customer")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction()
    {

        $this->_title($this->__("qsoft_customer"));
        $this->_title($this->__("Group"));
        $this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
        $model  = Mage::getModel("qsoft_customer/schedulebodyscan")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("group_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("customer");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("schedulebodyscan Manager"), Mage::helper("adminhtml")->__("schedulebodyscan Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("schedulebodyscan Description"), Mage::helper("adminhtml")->__("schedulebodyscan Description"));


        $this->_addContent($this->getLayout()->createBlock("qsoft_customer/adminhtml_schedulebodyscan_edit"))->_addLeft($this->getLayout()->createBlock("qsoft_customer/adminhtml_schedulebodyscan_edit_tabs"));

        $this->renderLayout();

    }
    public function saveAction()
    {

        $post_data=$this->getRequest()->getPost();

        if ($post_data) {
            try {
                $id = $this->getRequest()->getParam("id");
                if(!$id){
                    $post_data['created_at'] = date('Y-m-d h:i:s');
                }

                $post_data['interested'] = Mage::helper('core')->jsonEncode($post_data['checkSchedule']);

                $model = Mage::getModel("qsoft_customer/schedulebodyscan")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->save();

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("schedulebodyscan was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setGroupData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setGroupData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }

        }
        $this->_redirect("*/*/");
    }
}