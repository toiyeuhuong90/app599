<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 6:47 PM
 */
class QSoft_CustomerMeasure_Adminhtml_Measurement_TypeController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init Measurement Type
     *
     * @param string $idFieldName
     * @return $this
     */
    protected function _initType($idFieldName = 'id')
    {
        $this->_title($this->__('Measurement Types'))->_title($this->__('Manage Measurement Types'));

        $typeId = (int)$this->getRequest()->getParam($idFieldName);
        $type = Mage::getModel('qsoft_customermeasure/type');


        if ($typeId) {
            $type->load($typeId);
            $toggle_values = explode('-', $type->getData('toggle_value'));
            $type->setData('toggle_1', $toggle_values[0]);
            $type->setData('toggle_2', $toggle_values[1]);
        }
        
        Mage::register('current_measurement_type', $type);

        return $this;
    }


    public function indexAction()
    {
        $this->_title($this->__('Measurement Types'))->_title($this->__('Manage Measurement Types'));

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('qsoft_customermeasure/customer_measure');

        /**
         * Append measurement type block to content
         */
        $this->_addContent(
            $this->getLayout()->createBlock('qsoft_customermeasure/adminhtml_measurement_type', 'measurement_type')
        );

        /**
         * Add breadcrumb item
         */
        $this->_addBreadcrumb(Mage::helper('qsoft_customermeasure')->__('Measurement Types'), Mage::helper('qsoft_customermeasure')->__('Measurement Types'));
        $this->_addBreadcrumb(Mage::helper('qsoft_customermeasure')->__('Manage Measurement Types'), Mage::helper('qsoft_customermeasure')->__('Manage Measurement Types'));

        $this->renderLayout();
    }


    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Measurement Type edit action
     */
    public function editAction()
    {
        $this->_initType();
        $this->loadLayout();

        /* @var $type QSoft_CustomerMeasure_Model_Type */
        $type = Mage::registry('current_measurement_type');


        $this->_title($type->getMeasureId() ? $type->getTitle() : $this->__('New Measurement Type'));

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('qsoft_customermeasure/customer_measure');

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock("qsoft_customermeasure/adminhtml_measurement_type_edit"))->_addLeft($this->getLayout()->createBlock("qsoft_customermeasure/adminhtml_measurement_type_edit_tabs"));

        $this->renderLayout();
    }

    /**
     * Create new Measurement Type action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }


    /**
     * Create or save measurement Type.
     */

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();

        if ($data) {
            $redirectBack = $this->getRequest()->getParam('back', false);
            $this->_initType('measure_id');

            /* @var $type QSoft_CustomerMeasure_Model_Type */
            $type = Mage::registry('current_measurement_type');

            try {

                $model = Mage::getModel("qsoft_customermeasure/type");
                $model->addData($data)
                    ->setId($type->getMeasureId())
                    ->save();

                if ($data['type']) {
                    if ($data['type'] == 'toggle') {
                        $model->setData('toggle_value', $data['toggle_1'] . '-' . $data['toggle_2'])
                            ->save();
                    }
                }


                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper("qsoft_customermeasure")->__("Measurement Type was successfully saved"));
                Mage::getSingleton('adminhtml/session')->setMeasurementTypeData(false);

                if ($redirectBack) {
                    $this->_redirect('*/*/edit', array(
                        'id' => $type->getMeasureId(),
                        '_current' => true
                    ));
                    return;

                }

                $this->_redirect("*/*/");
                return;

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

                Mage::getSingleton('adminhtml/session')->setMeasurementTypeData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $type->getMeasureId()));

                return;

            }

        } else {

            $this->_forward('new');

        }
    }


    /**
     * Delete Measurement Type action
     */

    public function deleteAction()
    {
        $this->_initType();
        /* @var $type QSoft_CustomerMeasure_Model_Type */
        $type = Mage::registry('current_measurement_type');
        if ($type->getMeasureId()) {
            try {
                $type->setMeasureId($type->getMeasureId());
                $type->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qsoft_customermeasure')->__('Measurement Type has been deleted.'));

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*');
    }


    /**
     * Mass Measurement Type action
     */
    public function massDeleteAction()
    {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $type = Mage::getModel("qsoft_customermeasure/type");
                $type->setMeasureId($id)->delete();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper("qsoft_customermeasure")->__("Total of %d record(s) were deleted.", count($ids)));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'customermeasure_type.csv';
        $grid = $this->getLayout()->createBlock('qsoft_customermeasure/adminhtml_measurement_type_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export  grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'customermeasure_type.xml';
        $grid = $this->getLayout()->createBlock('qsoft_customermeasure/adminhtml_measurement_type_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('qsoft_customermeasure/measurement');
    }
}