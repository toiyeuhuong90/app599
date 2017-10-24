<?php

class QSoft_CustomerMeasure_Model_Observer
{
    public function saveCustomerMeasures($observer){
        
        if($postData = $observer->getEvent()->getRequest()->getPost()){
            $measurePostData = $postData['measure'];
            $dataSave = array();
            $customer = $observer->getEvent()->getCustomer();

            
            $valueCollection = Mage::getModel('qsoft_customermeasure/value')->getCollection();
            $valueCollection->addFieldToFilter('customer_id', $customer->getId());
            if($valueCollection->getFirstItem()->getId()){
                $id = $valueCollection->getFirstItem()->getId();
            }else{
                $id = null;

            }

            $model = Mage::getModel('qsoft_customermeasure/value');
            $model->load($id);

            $dataSave['measures'] = Mage::helper('core')->jsonEncode($measurePostData['item']);

            //for body scan
            if (isset($_FILES['body_scan']['name']) && $_FILES['body_scan']['name'] != '') {
                try {
                    $time = Mage::getModel('core/date')->timestamp();
                    $customerId = $customer->getId();
                    $uploader = new Varien_File_Uploader('body_scan');
                    $uploader->setAllowedExtensions(array('pdf'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $bodyscanName = time() . '.pdf';
                    $path = Mage::getBaseDir('media') . DS . 'bodyscan' . DS . $customerId . DS . $time . DS;

                    if($result = $uploader->save($path, $bodyscanName)){
                        $pathImage = $path . 'images' . DS;
                        if(!file_exists($pathImage)){
                            mkdir($pathImage, 0777);
                        }

                        $dataBodyScan = array();
                        if($model->getBodyScan()){
                            $dataBodyScan = Mage::helper('core')->jsonDecode($model->getBodyScan());
                        }
                        $bodyscan = array();
                        $bodyscan['description'] = $postData['body_description'];
                        $bodyscan['file'] = $path . $bodyscanName;
                        $bodyscan['url'] = str_replace('index.php/', '', Mage::getUrl().'media/bodyscan/'.$customerId.'/' . $bodyscanName);
                        $bodyscan['images'] = Mage::helper('qsoft_customermeasure')->saveCustomerBodyScan($customerId, $bodyscan['file'], $pathImage,$time);
                        $dataBodyScan[$time] = $bodyscan;
                        $dataSave['body_scan'] = Mage::helper('core')->jsonEncode($dataBodyScan);
                    }
                    //$data['item_icon'] = 'megamenu/'.$result['file'];
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }

            //for measure_csv
            if (isset($_FILES['measure_csv']['name']) && $_FILES['measure_csv']['name'] != '') {
                try {
                    $customerId = $customer->getId();
                    $uploader = new Varien_File_Uploader('measure_csv');
                    $uploader->setAllowedExtensions(array('csv'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $measure_csv = time() . '.csv';
                    $path = Mage::getBaseDir('media') . DS . 'measureCsv' . DS . $customerId . DS ;

                    if($result = $uploader->save($path, $measure_csv)){
                        $k = 1;
                        $maps = false;
                        if (($handle = fopen($path . $measure_csv, "r")) !== FALSE) {
                            while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
                                if ( $k == 2) {
                                    $maps = $row;
                                }
                                $k++;
                            }
                        }
                        $measures = array();
                        if($maps){
                            $dataSave['csv'] = str_replace('index.php/', '', Mage::getUrl().'media/measureCsv/'.$customerId.'/' . $measure_csv);
                            foreach ($postData['mapping'] as $key=>$measureId){
                                if($measureId){
                                    $measures[$measureId] = (float)$maps[$key];
                                }
                            }
                            $dataSave['measures'] = Mage::helper('core')->jsonEncode($measures);
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }

            $dataSave['unit'] = Mage::helper('core')->jsonEncode($measurePostData['unit']);
            $dataSave['customer_id'] = $customer->getId();
            $dataSave['updated_at'] = date('Y-m-d h:i:s');

            $model->addData($dataSave)
                ->save();
        }
    }
}