<?php

class QSoft_Custom_Adminhtml_LocationController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('system')->_addBreadcrumb(Mage::helper('adminhtml')->__('Inspire Me Management'), Mage::helper('adminhtml')->__('Inspire Me Management'));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Location'))->_title($this->__('Management'));

        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__('ProductOptions'));
        $this->_title($this->__('Inspire Me Management'));
        $this->_title($this->__('Edit Item'));

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('productdesign/inspireme')->load($id);

        if ($model->getId()) {

            Mage::register('inspireme_data', $model);

            $this->_initAction();
            $this->loadLayout();

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Inspire Me Management'), Mage::helper('adminhtml')->__('Inspire Me Management'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productdesign/adminhtml_inspireme_edit'))->_addLeft($this->getLayout()->createBlock('productdesign/adminhtml_inspireme_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper("productdesign")->__('Item does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {

        $this->_title($this->__('ProductOptions'));
        $this->_title($this->__('Inspire Me Management'));
        $this->_title($this->__('New Item'));

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('productdesign/inspireme')->load($id);

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('inspireme_data', $model);

        $this->loadLayout();
        $this->_initAction();

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Inspire Me Management'), Mage::helper('adminhtml')->__('Inspire Me Management'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Inspire Me Description'), Mage::helper('adminhtml')->__('Inspire Me Description'));


        $this->_addContent($this->getLayout()->createBlock('productdesign/adminhtml_inspireme_edit'))->_addLeft($this->getLayout()->createBlock('productdesign/adminhtml_inspireme_edit_tabs'));

        $this->renderLayout();

    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if ($data) {
            try {
                if (!$this->getRequest()->getParam('id')) {
                    $data['created_at'] = date('Y-m-d h:i:s');
                }
                $data['product_options_json'] = Mage::helper('core')->jsonEncode($data['options']);

                if($dataImage = Mage::app()->getRequest()->getParam('avartar_image')){
                    $imageName = time().'.png';
                    if(!is_dir(Mage::getBaseDir().DS."productDesign".DS."inspireme")){
                        mkdir(Mage::getBaseDir().DS."productDesign".DS."inspireme");
                    }
                    $dirImage = Mage::getBaseDir().DS."productDesign".DS."inspireme".DS.$imageName;
                    $img = str_replace('data:image/png;base64,', '', $dataImage);
                    $img = str_replace(' ', '+', $img);
                    $dataImage = base64_decode($img);
                    file_put_contents($dirImage, $dataImage);
                    $data['avartar_image'] = str_replace('index.php/', '', Mage::getBaseUrl()."productDesign/inspireme/".$imageName);
                }

                Mage::log($data, null, 'doan.log');
                $model = Mage::getModel('productdesign/inspireme')
                    ->addData($data)
                    ->setId($this->getRequest()->getParam('id'))
                    ->save();


                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Inspire me item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setInspiremeData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInspiremeData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }

        }
        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'inspireme.csv';
        $grid = $this->getLayout()->createBlock('productdesign/adminhtml_inspireme_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'inspireme.xml';
        $grid = $this->getLayout()->createBlock('productdesign/adminhtml_inspireme_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = Mage::getModel("productdesign/inspireme");
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