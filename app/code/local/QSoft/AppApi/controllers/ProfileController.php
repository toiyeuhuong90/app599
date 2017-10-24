<?php

class QSoft_AppApi_ProfileController extends Mage_Core_Controller_Front_Action
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

    public function updateAction()
    {
        $result = ['code' => 200];
        $token = $this->getRequest()->getHeader('token');
        if (!$token) {
            $result['status'] = 0;
            $result['message'] = 'Please login first.';
        } else {
            $check = $this->checkToken($token);
            if (!$check) {
                $result['status'] = 0;
                $result['message'] = 'Invalid token. Please login again.';
            } else {
                $ownerScan = $check;
                $errors = false;
                $post = $this->getRequest()->getPost();
                if (!isset($post['customer_id']) || $post['customer_id'] == '') {
                    $errors = true;
                    $result['message'] = 'Please post a customer id.';
                } else {
                    $customer = Mage::getModel('customer/customer')->load($post['customer_id']);
                    if (!$customer->getId()) {
                        $errors = true;
                        $result['message'] = 'It is not exits customer_id = ' . $post['customer_id'] . '.';
                    }
                }

                if (!isset($post['unit']) || $post['unit'] == '') {
                    $errors = true;
                    $result['message'] = 'Missing unit.';
                }

                if (!isset($post['age']) || $post['age'] == '') {
                    $errors = true;
                    $result['message'] = 'Missing data Age.';
                }

                if (!isset($post['weight']) || $post['weight'] == '') {
                    $errors = true;
                    $result['message'] = 'Missing data weight.';
                }


                if (!isset($post['height']) || $post['height'] == '') {
                    $errors = true;
                    $result['message'] = 'Missing data height.';
                }

                if (!isset($post['gender']) || $post['gender'] == '') {
                    $errors = true;
                    $result['message'] = 'Missing data gender.';
                }

                if (!isset($post['measures']) || $post['measures'] == '') {
                    $errors = true;
                    $result['message'] = 'Missing data measure.';
                } else {
                    $unit = json_decode($post['measures']);
                    if (json_last_error()) {
                        $errors = true;
                        $result['message'] = 'Data measure should be a json structure.';
                    }
                }

                if ($errors) {
                    $result['status'] = 0;
                } else {
                    $model = Mage::getModel('qsoft_customermeasure/value');
                    $model->load(null);
                    $post['updated_at'] = Mage::getSingleton('core/date')->date('Y-m-d h:i:s');
                    $post['thumbnail_customer'] = 'https://s3-us-west-1.amazonaws.com/evp.data/users/3/scan/2017-09-20+15%3A00%3A00/thumbnail.png';
                    $post['mesh_customer'] = 'https://s3-us-west-1.amazonaws.com/evp.data/user/3/scan/2017-09-20+15%3A00%3A00/model.obj';
                    $post['owner_scan'] = $ownerScan;

                    if($post['unit']==1){
                        $unit = array(
                            "weight" => '1',
                            "height" => '1'
                        );
                        $post['unit'] = json_encode($unit);
                        // calculation Metric BMI formula BMI = weight (kg) ÷ height2 (m2)
                        $height = $post['height'] / 100;
                        $metric = $post['weight'] / ($height * $height);

                        // Imperial (USA) BMI Formula BMI = weight (lb) ÷ height2 (in2) × 703
                        $weight = $post['weight'] * 2.026;
                        $height = $post['height'] * 0.3937;
                        $imperial = $weight / ($height * $height) * 703;


                    }else{
                        $unit = array(
                            "weight" => '2',
                            "height" => '2'
                        );
                        $post['unit'] = json_encode($unit);
                        $imperial = $post['weight'] / ($post['height'] * $post['height']) * 703;
                        // metric
                        $weight = $post['weight'] * 0.4536;
                        $height = $post['height'] * 0.0254;
                        $metric = $weight / ($height * $height);
                    }

                    // body mass "1. For men: LBM = (0.32810 × W) + (0.33929 × H) − 29.5336 2. For women: LBM = (0.29569 × W) + (0.41813 × H) − 43.2933"
                    if($post['gender']==1){
                        $lbm = 0.32810 * $post['weight'] + 0.33929 * $post['height'] - 29.5336;
                    } else {
                        $lbm = 0.29569 * $post['weight'] + 0.41813 * $post['height'] - 43.2933;
                    }

                    $sex = 0;
                    //Body weight
                    if($post['gender']==1){
                        $sex = 1;
                        $metricBodyWeight = 50 + 0.9 * ($post['height'] - 152);
                        $imperialBodyWeight = 110 + 2 * ($post['height'] - 152);
                    } else {
                        $metricBodyWeight = 45.5 + 0.9 * ($post['height'] - 152);
                        $imperialBodyWeight = 100 + 2 * ($post['height'] - 152);
                    }

                    // body fat
                    $metricBodyFat = 1.20 * $metric + 0.23 * $post['age'] - 10.8 * $sex;
                    $imperialBodyFat = 1.20 * $imperial + 0.23 * $post['age'] - 10.8 * $sex;


                    $post['bmi'] = Mage::helper('core')->jsonEncode(array('metric'=>$metric, 'imperial'=>$imperial));
                    $post['body_weight'] = Mage::helper('core')->jsonEncode(array('metric'=>$metricBodyWeight, 'imperial'=>$imperialBodyWeight));
                    $post['body_fat'] = Mage::helper('core')->jsonEncode(array('metric'=>$metricBodyFat, 'imperial'=>$imperialBodyFat));
                    $post['lean_mass'] = $lbm;

                    $model->addData($post)->save();

                    $result['status'] = 1;
                    $result['message'] = 'update successful!';
                }
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

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

    public function getAction()
    {
        $token = $this->getToken();
        $result = $this->messageCommon($this->API_CODE['401'], 0, 'Fail process', '');

        if ($token) {
            $customerId = $this->checkToken($token);

            if ($customerId) {
                if (isset($_REQUEST['customerId']) && $_REQUEST['customerId']) {
                    $customerId = $_REQUEST['customerId'];
                }

                $customer = Mage::getModel('customer/customer')->load($customerId);
                $data = $customer->getData();
                $role = 'customer';

                if ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_factory')) {
                    $role = 'factory';
                } elseif ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_merchant')) {
                    $role = 'merchant';
                }

                $birthday = $customer->getDob();
                $return = $arrMeasure = array();

                $modelMeasure = Mage::getModel('qsoft_customermeasure/type')->getCollection();
                $measures = $modelMeasure->getData();

                foreach ($measures as $measure) {
                    $arrMeasure[$measure['measure_id']]['id'] = $measure['measure_id'];
                    $arrMeasure[$measure['measure_id']]['title'] = $measure['title'];
                    $arrMeasure[$measure['measure_id']]['unit'] = $measure['unit'] == 1 ? 'unit_of_mass' : 'cubit';
                }

                $modelCustomer = Mage::getModel('qsoft_customermeasure/value')->getCollection()->addFieldToFilter('customer_id', $customerId)->setPageSize(10)->setOrder('id', 'DESC');

                $arrCustomers = $modelCustomer->getFirstItem();

                //get url avatar
                $avatar = $customer->getData('avatar_customer');
                if(strlen($customer->getData('avatar_customer')) > 8){
                    $avatar = explode('.com', $customer->getData('avatar_customer'));
                    $avatar = $avatar[1];
                    if (empty($avatar)){
                        $avatar = $customer->getData('avatar_customer');
                    }
                }
                $baseUrl = Mage::getStoreConfig('qsoft_appapi/group_setting/group_url');
                $return = array(
                    'id' => $customerId,
                    'email' => $data['email'],
                    'role' => $role,
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'gender' => $data['gender'],
                    'location' => $customer->getLocation(),
                    'weight' => $data['weight'],
                    'height' => $data['height'],
                    'birthday' => $birthday,
                    'avatar' => (($baseUrl.$avatar) ? ($baseUrl.$avatar) : Mage::getDesign()->getSkinUrl('images/default-avatar.jpg')),
                );

                if (!empty($arrCustomers)) {
                    //Todo: get Thumbnail && 3D-Mesh
                    $baseUrl = Mage::getStoreConfig('qsoft_appapi/group_setting/group_url');
                    $return['model'] = array(
                        'thumbnail' => $baseUrl.$arrCustomers['thumbnail_customer'],
                        '3d_mesh' => $baseUrl.$arrCustomers['mesh_customer']
                    );

                    if(strlen($arrCustomers['thumbnail_customer']) > 8 || strlen($arrCustomers['mesh_customer']) > 8){
                        $thumbnail = explode('.com', $arrCustomers['thumbnail_customer']);
                        $thumbnail = $thumbnail[1];
                        if (empty($thumbnail)){
                            $thumbnail = $arrCustomers['thumbnail_customer'];
                        }
                        $mesh = explode('.com', $arrCustomers['mesh_customer']);
                        $mesh = $mesh[1];
                        if (empty($mesh)){
                            $mesh = $arrCustomers['mesh_customer'];
                        }
                        $return['model'] = array(
                            'thumbnail' => $baseUrl.$thumbnail,
                            '3d_mesh' =>$baseUrl.$mesh
                        );
                    }

                    $return['owner'] = $this->getMerchant($arrCustomers['customer_id']);
                    if (empty($return['owner'])) {
                        $customer = Mage::getModel('customer/customer')->load($arrCustomers['customer_id']);
                        $data = $customer->getData();
                        $return['owner'] = array(
                            'id' => $arrCustomers['customer_id'],
                            'name' => $data['firstname'],
                            'location' => $customer->getLocation(),
                        );
                    }

                    $unit = json_decode($modelCustomer->getFirstItem()->getUnit());

                    foreach ($unit as $keyUnit => $unitValue) {
                        if ($keyUnit == 'weight') {
                            $return['unit_of_mass'] = array(
                                'value' => $unitValue,
                                'label' => ($unitValue == 1 ? 'Kg' : 'Lbs')
                            );
                        }

                        if ($keyUnit == 'height') {
                            $return['cubit'] = array(
                                'value' => $unitValue,
                                'label' => ($unitValue == 1 ? 'Cm' : 'Inch')
                            );
                        }
                    }

                    $measureValues = json_decode($arrCustomers['measures']);
                    foreach ($measureValues as $keyMeasure => $measureValue) {
                        $arrMeasure[$keyMeasure]['value'] = $measureValue;
                    }

                    $arrMap = [];

                    foreach ($arrMeasure as $arrData) {
                        array_push($arrMap, $arrData);
                    }

                    $return['value'] = $arrMap;

                }
                $arrMeg = [];
                array_push($arrMeg, $return);
                $result = $this->messageCommon($this->API_CODE[200], 1, '', $arrMeg);
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
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

    protected function getScanTime($customer_id, $beginDate, $endDate)
    {
        $result = '';
        $modelCustomer = Mage::getModel('qsoft_customermeasure/value')->getCollection()->addFieldToFilter('customer_id', $customer_id);
        if (!empty($modelCustomer)) {
            /**
             * Required startDate & stopDate
             */
            if (!empty($beginDate) && !empty($endDate)) {
                $modelCustomer
                    ->addFieldToFilter('updated_at', array('gteq' => $beginDate))
                    ->addFieldToFilter('updated_at', array('lteq' => $endDate));
            } /**
             * Required startDate
             */
            elseif (!empty($beginDate) && empty($endDate)) {
                $modelCustomer->addFieldToFilter('updated_at', array('gteq' => $beginDate));
            } /**
             * Required stopDate
             */
            elseif (!empty($endDate) && empty($beginDate)) {
                $modelCustomer->addFieldToFilter('updated_at', array('lteq' => $endDate));
            }

            //Sort Item
            $modelCustomer->setOrder('id', 'DESC');
            $result = $modelCustomer->getFirstItem()->getUpdatedAt();
        }
        return $result;
    }


    public function getAboutAction()
    {
        $result = $this->getLayout()->createBlock('cms/block')->setBlockId('about-us-app')->toHtml();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result, true));
    }

    public function getUsersAction()
    {
        $result = [];
        $token = '';

        foreach (getallheaders() as $key => $value) {
            if ($key == 'token') {
                $token = $value;
            }
        }

        if (!$token) {
            $result = $this->messageCommon($this->API_CODE['401'], 0, 'Fail process', '');
        } else {
            $customerId = $this->checkToken($token);
            if ($this->checkAuth($token)) {
                if ($customerId) {
                    if (isset($_REQUEST['customerId'])) {
                        $customerId = $_REQUEST['customerId'];
                    }

                    $customer = Mage::getModel('customer/customer')->load($customerId);
                    $data = $customer->getData();
                    $role = 'customer';

                    if ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_factory')) {
                        $role = 'factory';
                    } elseif ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_merchant')) {
                        $role = 'merchant';
                    }

                    $gender = $_REQUEST['gender'];
                    $keyword = $_REQUEST['keyword'];
                    $beginDate = $_REQUEST['from_date'];
                    $endDate = $_REQUEST['to_date'];

                    $users = Mage::getModel('customer/customer')->getCollection()->setOrder('id', 'DESC');

                    /**
                     * required input search name(or email)
                     */
                    if (!empty($keyword)) {

                        /**
                         * required input search-name(or email) and gender
                         */
                        $users->addAttributeToSelect("*")
                            ->addFieldToFilter(
                                array(
                                    array('attribute' => 'email', 'like' => '%' . $keyword . '%'),
                                    array('attribute' => 'firstname', 'like' => '%' . $keyword . '%'),
                                )
                            );
                        if (!empty($gender)) {
                            $users->addFieldToFilter('gender', $gender)
                                ->addAttributeToSort('firstname', 'ASC');
                        }

                    }
                    if (!empty($gender) && empty($keyword)) {
                        /**
                         * required input search-gender
                         */
                        $users->addFieldToFilter('gender', $gender)
                            ->addAttributeToSort('firstname', 'ASC');
                    }
                    if (!empty($beginDate) || !empty($endDate)) {
                        if (!empty($keyword) || !empty($gender)) {

                        }
                        $users->getSelect()->join(
                            array('measure' => qsoft_customer_measure_customer),
                            '(`measure`.`customer_id` = `e`.`entity_id`)', array()
                        )->group("e.entity_id");
                        if (!empty($beginDate) && !empty($endDate)) {
                            $users->getSelect()->where('`measure`.`updated_at` >= ' . '\'' . $beginDate . '\'')
                                ->where('`measure`.`updated_at` <= ' . '\'' . $endDate . '\'');
                        }
                        /**
                         * Required startDate
                         */
                        if (!empty($beginDate) && empty($endDate)) {
                            $users->getSelect()->where('`measure`.`updated_at` >= ' . '\'' . $beginDate . '\'');
                        }
                        /**
                         * Required stopDate
                         */
                        if (!empty($endDate) && empty($beginDate)) {
                            $users->getSelect()->where('`measure`.`updated_at` <= ' . '\'' . $endDate . '\'');
                        }
                        $users->getSelect()->order('measure.id DESC');
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
                    $users->getSelect()->limit($limit, $offset);
                    if (!empty($role) && $role == 'factory') {
                        $users->addAttributeToFilter('group_id', array('neq' => 5));
                        foreach ($users as $key => $user) {
                            $user->load($user['entity_id']);

                            //get url scan avatar
                            $scanAvatar = $customer->getData('avatar_customer');
                            if(strlen($customer->getData('avatar_customer')) > 8){
                                $scanAvatar = explode('.com', $customer->getData('avatar_customer'));
                                $scanAvatar = $scanAvatar[1];
                                if (empty($scanAvatar)){
                                    $scanAvatar = $customer->getData('avatar_customer');
                                }
                            }
                            //get url avatar
                            $avatar = $user['avatar_customer'];
                            if(strlen($customer->$user['avatar_customer']) > 8){
                                $avatar = explode('.com', $user['avatar_customer']);
                                $avatar = $avatar[1];
                                if (empty($avatar)){
                                    $avatar = $user['avatar_customer'];
                                }
                            }
                            $baseUrl = Mage::getStoreConfig('qsoft_appapi/group_setting/group_url');

                            $arrReturn[$key] = $user->toArray();
                            $arrReturn[$key]['role'] = $this->getRole($user['entity_id']);
                            $arrUser[$key]['avatar'] = (($baseUrl.$avatar) ? ($baseUrl.$avatar) : Mage::getDesign()->getSkinUrl('images/default-avatar.jpg'));

                            $arrReturn[$key]['scan_id'] = $customerId;
                            $arrReturn[$key]['scan_name'] = $data['firstname'];
                            $arrReturn[$key]['scan_time'] = $this->getScanTime($user['entity_id'], $beginDate, $endDate);
                            $arrUser[$key]['scan_avatar'] = (($baseUrl.$scanAvatar) ? ($baseUrl.$scanAvatar) : Mage::getDesign()->getSkinUrl('images/default-avatar.jpg'));

                            if ($arrReturn[$key] && $arrReturn != '') {
                                array_push($result, $arrReturn[$key]);
                            }
                        }
                    } else {
                        //Request ParentId => Return CustomerId (Only Merchant)
                        $modelCustomer = Mage::getModel('qsoft_appapi/relationship')->getCollection()->setPageSize(10)
                            ->addFieldToFilter('parent_id', $customerId)->setOrder('id', 'DESC');
                        $arrUid = [];
                        if (!empty($modelCustomer)) {
                            foreach ($modelCustomer as $k => $customers) {
                                $arrUid[] = $customers->getCustomerId();
                            }
                            $users->addFieldToFilter('entity_id', array('in' => $arrUid));
                            foreach ($users as $key => $item) {
                                $item->load($item['entity_id']);
                                $arrReturn[$key] = $item->toArray();

                                //get url scan avatar
                                $scanAvatar = $customer->getData('avatar_customer');
                                if(strlen($customer->getData('avatar_customer')) > 8){
                                    $scanAvatar = explode('.com', $customer->getData('avatar_customer'));
                                    $scanAvatar = $scanAvatar[1];
                                    if (empty($scanAvatar)){
                                        $scanAvatar = $customer->getData('avatar_customer');
                                    }
                                }
                                //get url avatar
                                $avatar = $item['avatar_customer'];
                                if(strlen($customer->$item['avatar_customer']) > 8){
                                    $avatar = explode('.com', $item['avatar_customer']);
                                    $avatar = $avatar[1];
                                    if (empty($avatar)){
                                        $avatar = $item['avatar_customer'];
                                    }
                                }
                                $baseUrl = Mage::getStoreConfig('qsoft_appapi/group_setting/group_url');

                                $arrReturn[$key]['scan_time'] = $this->getScanTime($item['entity_id'], $beginDate, $endDate);
                                $arrUser[$key]['avatar'] = (($baseUrl.$avatar) ? ($baseUrl.$avatar) : Mage::getDesign()->getSkinUrl('images/default-avatar.jpg'));
                                $arrReturn[$key]['role'] = $this->getRole($item['entity_id']);

                                $arrReturn[$key]['scan_id'] = $customerId;
                                $arrReturn[$key]['scan_name'] = $data['firstname'];
                                $arrReturn[$key]['scan_time'] = $this->getScanTime($item['entity_id'], $beginDate, $endDate);
                                $arrUser[$key]['scan_avatar'] = (($baseUrl.$scanAvatar) ? ($baseUrl.$scanAvatar) : Mage::getDesign()->getSkinUrl('images/default-avatar.jpg'));
                                if ($arrReturn[$key] && $arrReturn != '') {
                                    array_push($result, $arrReturn[$key]);
                                }
                            }
                        }
                    }

                    $result = $this->messageCommon($this->API_CODE[200], 1, '', $result);
                } else {
                    $result = $this->messageCommon($this->API_CODE[401], 0, 'Fail process', '');
                }
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function getTermAction()
    {
        $result = $this->getLayout()->createBlock('cms/block')->setBlockId('term-and-condition')->toHtml();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result, true));
    }
}