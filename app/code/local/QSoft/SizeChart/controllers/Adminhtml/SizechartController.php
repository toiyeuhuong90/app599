<?php

class QSoft_SizeChart_Adminhtml_SizechartController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("sizechart/sizechart")->_addBreadcrumb(Mage::helper("adminhtml")->__("Sizechart  Manager"), Mage::helper("adminhtml")->__("Sizechart Manager"));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__("SizeChart"));
        $this->_title($this->__("Manager Sizechart"));

        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__("SizeChart"));
        $this->_title($this->__("Sizechart"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("sizechart/sizechart")->load($id);
        if ($model->getId()) {
            Mage::register("sizechart_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("sizechart/sizechart");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Sizechart Manager"), Mage::helper("adminhtml")->__("Sizechart Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Sizechart Description"), Mage::helper("adminhtml")->__("Sizechart Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("sizechart/adminhtml_sizechart_edit"))->_addLeft($this->getLayout()->createBlock("sizechart/adminhtml_sizechart_edit_tabs"));
            $this->renderLayout();
        } else {
            $this->_redirect("*/*/");
        }
    }

    public function newAction()
    {

        $this->_title($this->__("SizeChart"));
        $this->_title($this->__("Sizechart"));
        $this->_title($this->__("New Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("sizechart/sizechart")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("sizechart_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("sizechart/sizechart");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Sizechart Manager"), Mage::helper("adminhtml")->__("Sizechart Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Sizechart Description"), Mage::helper("adminhtml")->__("Sizechart Description"));


        $this->_addContent($this->getLayout()->createBlock("sizechart/adminhtml_sizechart_edit"))->_addLeft($this->getLayout()->createBlock("sizechart/adminhtml_sizechart_edit_tabs"));

        $this->renderLayout();

    }

    public function saveAction()
    {

        $post_data = $this->getRequest()->getPost();


        if ($post_data) {

            try {

                if(!$this->getRequest()->getParam("id")){
                    $post_data['identifier'] = Mage::getSingleton('customer/customer')->generatePassword(20);
                }else{
                    $post_data['identifier'] = Mage::getModel("sizechart/sizechart")->load($this->getRequest()->getParam("id"))->getIdentifier();
                }

                $model = Mage::getModel("sizechart/sizechart")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->save();

                $content = '
<div class="qs-tab-change-measure">
    <div class="qs-row-measure"><input value="cm" id="chart-cm" type="radio" checked="checked" name="change-measure" /> <label for="chart-cm">cm</label></div>
    <div class="qs-row-measure"><input value="inch" id="chart-inch" type="radio" name="change-measure" /> <label for="chart-inch">inch</label></div>
</div>
<div class="qs-tab-pane-content">
    <div class="qs-table-measure">
        <div class="row">
            <div class="col-md-3">
                <div class="qs-img-measure"><img alt="" src="'.$post_data['main_image'].'" /></div>
            </div>
            <div class="col-md-9">
                <div id="sizechart_cm">
                    <img alt="'.$post_data['name'].'" title="'.$post_data['name'].'" src="'.$post_data['image_cm'].'"/>
                </div>
                <div id="sizechart_inch" style="display: none">
                    <img alt="'.$post_data['name'].'" title="'.$post_data['name'].'" src="'.$post_data['image_inch'].'"/>
                </div>
            </div>
        </div>
    </div>
</div>';
                $data = array(
                    'title' => $post_data['name'],
                    'identifier' => $model->getIdentifier(),
                    'content' => $content,
                    'is_active' => 1,
                    'is_size_chart' => 1,
                    'stores' => array(0)
                );

                $model = Mage::getModel('cms/block')->load($model->getIdentifier());
                if($model->getId()){
                    $model->setContent($content)->setTitle($post_data['name'])
                        ->save();
                }else{
                    Mage::getModel('cms/block')->setData($data)->save();
                }
                
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Sizechart was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setSizechartData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setSizechartData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }

        }
        $this->_redirect("*/*/");
    }


    public function deleteAction()
    {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = Mage::getModel("sizechart/sizechart");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }


}
