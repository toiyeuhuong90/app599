<?php

class QSoft_AppApi_SearchController extends Mage_Core_Controller_Front_Action
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

    public function searchAction()
    {
        $result = array();
        $token = '';

        foreach (getallheaders() as $key => $value) {
            if ($key == 'token') {
                $token = $value;
            }
        }

        if (!$token) {
            $result = $this->messageCommon($this->API_CODE[401], 0, 'Fail process', '');
        } else {
            $customerId = $this->checkToken($token);
            if ($this->checkAuth($token)) {
                if ($customerId) {
                    if (isset($_REQUEST['customerId'])) {
                        $customerId = $_REQUEST['customerId'];
                    }
                    $customer = Mage::getModel('customer/customer')->load($customerId);
                    $data = $customer->getData();
                    $keyword = $_REQUEST['keyword'];

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

                        $arrUser[$key] = $user->toArray();
                        $arrUser[$key]['role'] = $this->getRole($user['entity_id']);
                        $arrUser[$key]['avatar'] = (($baseUrl.$avatar) ? ($baseUrl.$avatar) : Mage::getDesign()->getSkinUrl('images/default-avatar.jpg'));

                        $arrUser[$key]['scan_id'] = $customerId;
                        $arrUser[$key]['scan_name'] = $data['firstname'];
                        $arrUser[$key]['scan_time'] = $this->getScanTime($user['entity_id']);
                        $arrUser[$key]['scan_avatar'] = (($baseUrl.$scanAvatar) ? ($baseUrl.$scanAvatar) : Mage::getDesign()->getSkinUrl('images/default-avatar.jpg'));

                        if($arrUser[$key]) {
                            array_push($result, $arrUser[$key]);
                        }
                    }
                    $result = $this->messageCommon($this->API_CODE[200], 1, '', $result);
                } else {
                    $result = $this->messageCommon($this->API_CODE[401], 0, 'Fail process', '');
                }
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result, true));
    }
}