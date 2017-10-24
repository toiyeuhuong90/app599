<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/13/2016
 * Time: 3:43 PM
 */
class QSoft_CustomerMeasure_Adminhtml_MeasurementController extends Mage_Adminhtml_Controller_Action
{
    public function getMeasurementTypeAction()
    {

    }

    public function getMappingAction(){
        if($data = $this->getRequest()->getPost()){
            if(isset($_FILES['measure_csv'])){
                $allowedExts = array("csv");
                $temp = explode(".", $_FILES["measure_csv"]["name"]);
                $extension = end($temp);
                $k = 1;
                $result = array();
                $maps = false;
                if(in_array($extension,$allowedExts)){

                    if (($handle = fopen($_FILES["measure_csv"]["tmp_name"], "r")) !== FALSE) {
                        while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
                            if ( $k == 1) {
                                $maps = $row;
                            }
                            $k++;
                        }

                        $block = $this->getLayout()->createBlock('qsoft_customermeasure/adminhtml_measurement_mappingfield')
                            ->setTemplate('qsoft/customer/measure/mapping.phtml')->setDataCsv($maps);
                        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('content'=>$block->toHtml())));
                    }
                }else{
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('error'=>'Invalid file type')));
                }

            }

        }
    }

    public function removeHistoryAction(){
        if($id=$this->getRequest()->getParam('id')){
            /* @var $model QSoft_CustomerMeasure_Model_Value */
            $model = Mage::getModel('qsoft_customermeasure/value')->load($id);

            $measurements = Mage::helper('core')->jsonDecode($model->getMeasures());
            $measurePost = $this->getRequest()->getParam('measure_id');
            $measurements[$measurePost] = 0;
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $write->query('update qsoft_customer_measure_customer set measures=? where id=?', array(Mage::helper('core')->jsonEncode($measurements), $id));

            $result['status'] = 1;
            $result['message'] = 'success';
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
}