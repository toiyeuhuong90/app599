<?php

class QSoft_AppApi_ScanController extends Mage_Core_Controller_Front_Action
{
    const API_MESSAGE_SUCCESS = "success process";
    const API_MESSAGE_FAIL = "fail process";

    protected $API_CODE = array(
        '200' => 200, // success
        '404' => 404, // not found
        '401' => 401, // authentification
        '301' => 301, // data wrong
        '305' => 305, // data exist
        '303' => 303, // error api fault
    );

    protected function messageCommon($code, $status, $message, $data)
    {
        $message = array(
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data
        );

        return $message;
    }

    protected function getToken()
    {
        $token = '';

        foreach (getallheaders() as $key => $value) {
            if ($key == 'token') {
                $token = $value;
            }
        }

        return $token;
    }

    protected function checkToken($token)
    {
        $model = Mage::getModel('qsoft_appapi/token');

        $collection = $model->getCollection()
            ->addFieldToFilter('token', $token);

        if ($collection->getFirstItem()->getCustomerId()) {
            return $collection->getFirstItem()->getCustomerId();
        }

        return false;
    }

    protected function checkAuth($token)
    {
        $model = Mage::getModel('qsoft_appapi/token');

        $collection = $model->getCollection()
            ->addFieldToFilter('token', $token);

        if ($collection->getFirstItem()->getCustomerId()) {
            $customer = Mage::getModel('customer/customer')->load($collection->getFirstItem()->getCustomerId());
            $data = $customer->getData();

            if ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_factory') && $data['group_id'] || Mage::getStoreConfig('qsoft_appapi/group_setting/group_merchant')) {
                return true;
            }
        }

        return false;
    }

    protected function getMeasure($customer_id)
    {
        $return = $arrMeasure = array();

        $modelMeasure = Mage::getModel('qsoft_customermeasure/type')->getCollection();
        $measures = $modelMeasure->getData();

        foreach ($measures as $measure) {
            $arrMeasure[$measure['measure_id']]['title'] = $measure['title'];
            $arrMeasure[$measure['measure_id']]['unit'] = $measure['unit'] == 1 ? 'unit_of_mass' : 'cubit';
        }

        $modelCustomer = Mage::getModel('qsoft_customermeasure/value')->getCollection()->addFieldToFilter('customer_id', $customer_id)->setOrder('id', 'DESC');
        if ($modelCustomer->getFirstItem()->getUnit()) {

            $return['scan_time'] = $modelCustomer->getFirstItem()->getUpdatedAt();
            $unit = json_decode($modelCustomer->getFirstItem()->getUnit());

            foreach ($unit as $keyUnit => $unitValue) {
                if ($keyUnit == 'weight') {
                    $return['unit']['unit_of_mass'] = array(
                        'value' => $unitValue,
                        'label' => ($unitValue == 1 ? 'Kg' : 'Lbs')
                    );
                }

                if ($keyUnit == 'height') {
                    $return['unit']['cubit'] = array(
                        'value' => $unitValue,
                        'label' => ($unitValue == 1 ? 'Cm' : 'Inch')
                    );
                }
            }

            $measureValues = json_decode($modelCustomer->getFirstItem()->getMeasures());
            foreach ($measureValues as $keyMeasure => $measureValue) {
                $arrMeasure[$keyMeasure]['value'] = $measureValue;
            }

            $return['value'] = $arrMeasure;
        }

        return $return;
    }

    protected function getMerchant($customer_id)
    {
        $return = array();

        $modelCustomer = Mage::getModel('qsoft_appapi/relationship')->getCollection()->addFieldToFilter('customer_id', $customer_id)->setOrder('id', 'DESC');

        if ($modelCustomer->getFirstItem()->getParentId()) {
            $return['id'] = $modelCustomer->getFirstItem()->getParentId();

            $customer = Mage::getModel('customer/customer')->load($modelCustomer->getFirstItem()->getParentId());
            $return['name'] = $customer->getName();
            $return['location'] = $customer->getLocation();
        }

        return $return;
    }

    protected function getRole($customer_id)
    {

        $customer = Mage::getModel('customer/customer')->load($customer_id);
        $data = $customer->getData();
        $role = 'customer';

        if ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_factory')) {
            $role = 'factory';
        } elseif ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_merchant')) {
            $role = 'merchant';
        }

        return $role;
    }

    protected function getScanTime($customer_id)
    {
        $result = [];
        $modelCustomer = Mage::getModel('qsoft_customermeasure/value')->getCollection()->addFieldToFilter('customer_id', $customer_id);
        if (!empty($modelCustomer)) {
            $modelCustomer->setOrder('id', 'DESC');
            $result = $modelCustomer->getFirstItem()->getUpdatedAt();
        }
        return $result;
    }

    protected function historyAction()
    {
        $token = $this->getToken();
        $result = $this->messageCommon($this->API_CODE[401], 0, 'Fail process', '');

        if ($this->checkAuth($token)) {
            $customer_id = $this->checkToken($token);
            if ($customer_id) {
                if (isset($_REQUEST['customerId']) && $_REQUEST['customerId']) {
                    $customer_id = $_REQUEST['customerId'];
                }
                $return = $arrMeasure = array();

                $modelMeasure = Mage::getModel('qsoft_customermeasure/type')->getCollection();
                $measures = $modelMeasure->getData();

                foreach ($measures as $measure) {
                    $arrMeasure[$measure['measure_id']]['id'] = $measure['measure_id'];
                    $arrMeasure[$measure['measure_id']]['title'] = $measure['title'];
                    $arrMeasure[$measure['measure_id']]['unit'] = $measure['unit'] == 1 ? 'unit_of_mass' : 'cubit';
                    $arrMeasure[$measure['measure_id']]['description'] = $measure['description'];
                }

                $modelCustomer = Mage::getModel('qsoft_customermeasure/value')->getCollection()->addFieldToFilter('customer_id', $customer_id)->setOrder('id', 'DESC');

                if (isset($_REQUEST['id']) && $_REQUEST['id']) {
                    $modelCustomer->addFieldToFilter('id', $_REQUEST['id']);
                }

                if (isset($_REQUEST['limit']) && $_REQUEST['limit']) {
                    $limit = $_REQUEST['limit'];
                } else {
                    $limit = 10;
                }

                if (isset($_REQUEST['offset']) && $_REQUEST['offset']) {
                    $offset = $_REQUEST['offset'];
                } else {
                    $offset = 0;
                }
                $modelCustomer->getSelect()->limit($limit, $offset);

                $arrCustomers = $modelCustomer->getData();

                $arrMerge = [];
                if (!empty($arrCustomers)) {
                    foreach ($arrCustomers as $keyArr => $arrCustomer) {
                        $return[$keyArr]['id'] = $arrCustomer['id'];
                        $return[$keyArr]['scan_time'] = $arrCustomer['updated_at'];
                        $return[$keyArr]['height'] = $arrCustomer['height'];
                        $return[$keyArr]['weight'] = $arrCustomer['weight'];
                        $return[$keyArr]['bmi'] = array(
                            'id' => 9996,
                            'value' => json_decode($arrCustomer['bmi']),
                        );
                        $return[$keyArr]['lean_mass'] = array(
                            'id' => 9997,
                            'value' => $arrCustomer['lean_mass'],
                        );
                        $return[$keyArr]['body_fat'] = array(
                            'id' => 9998,
                            'value' => json_decode($arrCustomer['body_fat'])
                        );
                        $return[$keyArr]['body_weight'] = array(
                            'id' => 9999,
                            'value' => json_decode($arrCustomer['body_weight'])
                        );

                        //Todo: get Thumbnail && 3D-Mesh
                        $baseUrl = Mage::getStoreConfig('qsoft_appapi/group_setting/group_url');
                        $return[$keyArr]['model'] = array(
                            'thumbnail' => $baseUrl.$arrCustomer['thumbnail_customer'],
                            '3d_mesh' => $baseUrl.$arrCustomer['mesh_customer']
                        );

                        if(strlen($arrCustomer['thumbnail_customer']) > 8 || strlen($arrCustomer['mesh_customer']) > 8){
                            $thumbnail = explode('.com', $arrCustomer['thumbnail_customer']);
                            $thumbnail = $thumbnail[1];
                            if (empty($thumbnail)){
                                $thumbnail = $arrCustomer['thumbnail_customer'];
                            }
                            $mesh = explode('.com', $arrCustomer['mesh_customer']);
                            $mesh = $mesh[1];
                            if (empty($mesh)){
                                $mesh = $arrCustomer['mesh_customer'];
                            }
                            $return[$keyArr]['model'] = array(
                                'thumbnail' => $baseUrl.$thumbnail,
                                '3d_mesh' =>$baseUrl.$mesh
                            );
                        }

                        $return[$keyArr]['owner'] = $this->getMerchant($arrCustomer['customer_id']);
                        if (empty($return[$keyArr]['owner'])) {
                            $customer = Mage::getModel('customer/customer')->load($arrCustomer['customer_id']);
                            $data = $customer->getData();
                            $return[$keyArr]['owner'] = array(
                                'id' => $arrCustomer['customer_id'],
                                'name' => $data['firstname'],
                                'location' => $customer->getLocation(),
                            );
                        }

                        if (isset($_REQUEST['id']) && $_REQUEST['id']) {
                            $unit = json_decode($arrCustomer['unit']);

                            foreach ($unit as $keyUnit => $unitValue) {
                                if ($keyUnit == 'weight') {
                                    $return[$keyArr]['unit']['unit_of_mass'] = array(
                                        'value' => $unitValue,
                                        'label' => ($unitValue == 1 ? 'Kg' : 'Lbs')
                                    );
                                }

                                if ($keyUnit == 'height') {
                                    $return[$keyArr]['unit']['cubit'] = array(
                                        'value' => $unitValue,
                                        'label' => ($unitValue == 1 ? 'Cm' : 'Inch')
                                    );
                                }
                            }

                            $measureValues = json_decode($arrCustomer['measures']);
                            foreach ($measureValues as $keyMeasure => $measureValue) {
                                $arrMeasure[$keyMeasure]['value'] = $measureValue;
                            }

                            $arrMap = [];

                            foreach ($arrMeasure as $arrData) {
                                array_push($arrMap, $arrData);
                            }

                            $return[$keyArr]['value'] = $arrMap;
                        }
                    }
                }

                $result = $this->messageCommon($this->API_CODE[200], 1, '', $return);
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function getMeasureList()
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $data = $read->fetchAll('select measure_id, title, unit, min_value,max_value from qsoft_customer_measure');
        $result = $this->messageCommon($this->API_CODE['200'], 'success', $data);
        $result['status'] = 1;
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function getScanLocationsAction()
    {
        $result = [];
        $token = '';

        foreach (getallheaders() as $key => $value) {
            if ($key == 'token') {
                $token = $value;
            }
        }

        if (!$token) {
            $result = $this->messageCommon($this->API_CODE[401], 0, 'Fail process', '');
        } else {
            if ($this->checkAuth($token)) {
                $users = Mage::getModel('customer/customer')->getCollection()->setOrder('id', 'DESC');

                if (isset($_REQUEST['limit']) && $_REQUEST['limit']) {
                    $limit = $_REQUEST['limit'];
                } else {
                    $limit = 10;
                }

                if (isset($_REQUEST['offset']) && $_REQUEST['offset']) {
                    $offset = $_REQUEST['offset'];
                } else {
                    $offset = 0;
                }
                $users->getSelect()->limit($limit, $offset);

                $users->addAttributeToFilter('group_id', 5);

                foreach ($users as $key => $user) {
                    $user->load($user['entity_id']);
                    $arrReturn[$key] = $user->toArray();
                    $arrReturn[$key]['role'] = $this->getRole($user['entity_id']);
                    $arrReturn[$key]['scan_time'] = $this->getScanTime($user['entity_id']);
                    $arrReturn[$key]['location'] = $user->getLocation();
                    $arrReturn[$key]['avatar'] = ($user['avatar_customer'] ? $user['avatar_customer'] : Mage::getDesign()->getSkinUrl('images/default-avatar.jpg'));
                    if ($arrReturn[$key]) {
                        array_push($result, $arrReturn[$key]);
                    }
                }
                $result = $this->messageCommon($this->API_CODE[200], 1, '', $result);
            } else {
                $result = $this->messageCommon($this->API_CODE[401], 0, 'Fail process', '');
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function historyOfEachAction()
    {
        $token = $this->getToken();
        $result = $this->messageCommon($this->API_CODE[401], 0, 'Fail process', '');

        if ($this->checkAuth($token)) {
            $customer_id = $this->checkToken($token);
            if ($customer_id) {
                $return = $arrMeasure = array();

                $customer = Mage::getModel('customer/customer')->load($customer_id);
                $data = $customer->getData();
                $role = 'customer';

                if ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_factory')) {
                    $role = 'factory';
                } elseif ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_merchant')) {
                    $role = 'merchant';
                }

                $modelMeasure = Mage::getModel('qsoft_customermeasure/type')->getCollection();
                $measures = $modelMeasure->getData();

                $measureId = $this->getRequest()->getParam('measureId') ? $this->getRequest()->getParam('measureId') : '';

                if ($measureId) {
                    foreach ($measures as $measure) {
                        if ($measureId == $measure['measure_id']) {
                            $arrMeasure[$measure['measure_id']]['unit'] = $measure['unit'] == 1 ? 'unit_of_mass' : 'cubit';
                            $arrMeasure[$measure['measure_id']]['title'] = $measure['title'];
                        }
                    }
                } else {
                    foreach ($measures as $measure) {
                        $arrMeasure[$measure['measure_id']]['unit'] = $measure['unit'] == 1 ? 'unit_of_mass' : 'cubit';
                        $arrMeasure[$measure['measure_id']]['title'] = $measure['title'];
                    }
                }


                $modelCustomer = Mage::getModel('qsoft_customermeasure/value')->getCollection()->addFieldToFilter('customer_id', $customer_id)->setOrder('id', 'DESC');
                if (!empty($role) && $role == 'factory') {
                    $modelCustomer = Mage::getModel('qsoft_customermeasure/value')->getCollection()->setOrder('id', 'DESC');
                }

                if (isset($_REQUEST['limit']) && $_REQUEST['limit']) {
                    $limit = $_REQUEST['limit'];
                } else {
                    $limit = 10;
                }

                if (isset($_REQUEST['offset']) && $_REQUEST['offset']) {
                    $offset = $_REQUEST['offset'];
                } else {
                    $offset = 0;
                }
                $modelCustomer->getSelect()->limit($limit, $offset);

                $arrCustomers = $modelCustomer->getData();
;
                if (!empty($arrCustomers)) {
                    foreach ($arrCustomers as $keyArr => $arrCustomer) {
                        $return[$keyArr]['measureId'] = $measureId;

                        $return[$keyArr]['id'] = $arrCustomer['id'];
                        $return[$keyArr]['scan_time'] = $arrCustomer['updated_at'];
                        $return[$keyArr]['height'] = $arrCustomer['height'];
                        $return[$keyArr]['weight'] = $arrCustomer['weight'];
                        $return[$keyArr]['bmi'] = array(
                            'id' => 9996,
                            'value' => json_decode($arrCustomer['bmi']),
                        );
                        $return[$keyArr]['lean_mass'] = array(
                            'id' => 9997,
                            'value' => $arrCustomer['lean_mass'],
                        );
                        $return[$keyArr]['body_fat'] = array(
                            'id' => 9998,
                            'value' => json_decode($arrCustomer['body_fat'])
                        );
                        $return[$keyArr]['body_weight'] = array(
                            'id' => 9999,
                            'value' => json_decode($arrCustomer['body_weight'])
                        );

                        //Todo: get Thumbnail && 3D-Mesh
                        $baseUrl = Mage::getStoreConfig('qsoft_appapi/group_setting/group_url');
                        $return[$keyArr]['model'] = array(
                            'thumbnail' => $baseUrl.$arrCustomer['thumbnail_customer'],
                            '3d_mesh' => $baseUrl.$arrCustomer['mesh_customer']
                        );

                        if(strlen($arrCustomer['thumbnail_customer']) > 8 || strlen($arrCustomer['mesh_customer']) > 8){
                            $thumbnail = explode('.com', $arrCustomer['thumbnail_customer']);
                            $thumbnail = $thumbnail[1];
                            if (empty($thumbnail)){
                                $thumbnail = $arrCustomer['thumbnail_customer'];
                            }
                            $mesh = explode('.com', $arrCustomer['mesh_customer']);
                            $mesh = $mesh[1];
                            if (empty($mesh)){
                                $mesh = $arrCustomer['mesh_customer'];
                            }
                            $return[$keyArr]['model'] = array(
                                'thumbnail' => $baseUrl.$thumbnail,
                                '3d_mesh' =>$baseUrl.$mesh
                            );
                        }

                        $return[$keyArr]['owner'] = $this->getMerchant($arrCustomer['customer_id']);
                        if (empty($return[$keyArr]['owner'])) {
                            $customer = Mage::getModel('customer/customer')->load($arrCustomer['customer_id']);
                            $data = $customer->getData();
                            $return[$keyArr]['owner'] = array(
                                'id' => $arrCustomer['customer_id'],
                                'name' => $data['firstname'],
                                'location' => $customer->getLocation(),
                            );
                        }

                        $unit = json_decode($arrCustomer['unit']);

                        foreach ($unit as $keyUnit => $unitValue) {
                            if ($keyUnit == 'weight') {
                                $return[$keyArr]['unit_of_mass'] = array(
                                    'value' => $unitValue,
                                    'label' => ($unitValue == 1 ? 'Kg' : 'Lbs')
                                );
                            }

                            if ($keyUnit == 'height') {
                                $return[$keyArr]['cubit'] = array(
                                    'value' => $unitValue,
                                    'label' => ($unitValue == 1 ? 'Cm' : 'Inch')
                                );
                            }
                        }

                        $measureValues = json_decode($arrCustomer['measures']);

                        if ($measureId) {
                            foreach ($measureValues as $keyMeasure => $measureValue) {
                                if ($measureId == $keyMeasure) {
//                                    $arrMeasure[$keyMeasure]['value'] = $measureValue;
                                    $arrMeasure[$keyMeasure] = $measureValue;
                                }
                            }
                        } else {
                            foreach ($measureValues as $keyMeasure => $measureValue) {
//                                $arrMeasure[$keyMeasure]['value'] = $measureValue;
                                $arrMeasure[$keyMeasure] = $measureValue;
                            }
                        }

                        $arrMap = [];

                        foreach ($arrMeasure as $arrData) {
                            ($arrMap = $arrData);
                        }
                        $return[$keyArr]['value'] = $arrMap;
                        if (empty($arrMap)) {
                            unset($return[$keyArr]['value']);
                        }
                    }
                }

                $result = $this->messageCommon($this->API_CODE[200], 1, '', $return);
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}