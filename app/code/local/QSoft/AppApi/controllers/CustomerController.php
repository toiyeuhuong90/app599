<?php

require_once('Mage/Customer/controllers/AccountController.php');

class QSoft_AppApi_CustomerController extends Mage_Customer_AccountController
{
    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        // a brute-force protection here would be nice

        $this->_preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        $action = strtolower($this->getRequest()->getActionName());
        $openActions = array(
            'create',
            'login',
            'logoutsuccess',
            'forgotpassword',
            'forgotpasswordpost',
            'changeforgotten',
            'resetpassword',
            'resetpasswordpost',
            'confirm',
            'confirmation',
            'socialLogin',
            'createProfile',
            'changePassword',
            'editPost',
            'logOut',
            'saveMeasurement',
            'createNewMeasure',

        );
        $pattern = '/^(' . implode('|', $openActions) . ')/i';

        if (!preg_match($pattern, $action)) {
            if (!$this->_getSession()->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        } else {
            $this->_getSession()->setNoReferer(true);
        }
    }

    /**
     * Dispatch event before action
     *
     * @return void
     */
    protected function _preDispatch()
    {
        if (!$this->getFlag('', self::FLAG_NO_CHECK_INSTALLATION)) {
            if (!Mage::isInstalled()) {
                $this->setFlag('', self::FLAG_NO_DISPATCH, true);
                $this->_redirect('install');
                return;
            }
        }

        // Prohibit disabled store actions
        if (Mage::isInstalled() && !Mage::app()->getStore()->getIsActive()) {
            Mage::app()->throwStoreException();
        }

        if ($this->_rewrite()) {
            return;
        }

        if (!$this->getFlag('', self::FLAG_NO_START_SESSION)) {
            $checkCookie = in_array($this->getRequest()->getActionName(), $this->_cookieCheckActions)
                && !$this->getRequest()->getParam('nocookie', false);
            $cookies = Mage::getSingleton('core/cookie')->get();
            /** @var $session Mage_Core_Model_Session */
            $session = Mage::getSingleton('core/session', array('name' => $this->_sessionNamespace))->start();

            if (empty($cookies)) {
                if ($session->getCookieShouldBeReceived()) {
                    $this->setFlag('', self::FLAG_NO_COOKIES_REDIRECT, true);
                    $session->unsCookieShouldBeReceived();
                    $session->setSkipSessionIdFlag(true);
                } elseif ($checkCookie) {
                    if (isset($_GET[$session->getSessionIdQueryParam()]) && Mage::app()->getUseSessionInUrl()
                        && $this->_sessionNamespace != Mage_Adminhtml_Controller_Action::SESSION_NAMESPACE
                    ) {
                        $session->setCookieShouldBeReceived(true);
                    } else {
                        $this->setFlag('', self::FLAG_NO_COOKIES_REDIRECT, true);
                    }
                }
            }
        }

        Mage::app()->loadArea($this->getLayout()->getArea());

//        if ($this->getFlag('', self::FLAG_NO_COOKIES_REDIRECT)
//            && Mage::getStoreConfig('web/browser_capabilities/cookies')
//        ) {
//            $this->_forward('noCookies', 'index', 'core');
//            return;
//        }
//
//        if ($this->getFlag('', self::FLAG_NO_PRE_DISPATCH)) {
//            return;
//        }

        Varien_Autoload::registerScope($this->getRequest()->getRouteName());

        Mage::dispatchEvent('controller_action_predispatch', array('controller_action' => $this));
        Mage::dispatchEvent('controller_action_predispatch_' . $this->getRequest()->getRouteName(),
            array('controller_action' => $this));
        Mage::dispatchEvent('controller_action_predispatch_' . $this->getFullActionName(),
            array('controller_action' => $this));
    }

    /**
     * Change customer password action
     */
    public function editPostAction()
    {
        $result = ['code' => 200];
        $currentToken = Mage::app()->getRequest()->getHeader('token');
        if ($customer = $this->getCustomerFromToken($currentToken)) {
            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                /** @var $customer Mage_Customer_Model_Customer */
                if(isset($post['customer_id']) && $post['customer_id']!=''){
                    $customer = $post['customer_id'];
                }
                $customer = Mage::getModel('customer/customer')->load($customer);
                /* @var $customer Mage_Customer_Model_Customer */
                if(!$post['email']){
                    $this->getRequest()->setPost('email', $customer->getEmail());
                }

                if(!$post['firstname']){
                    $this->getRequest()->setPost('firstname', $customer->getFirstname());
                }

                if(!$post['lastname']){
                    $this->getRequest()->setPost('lastname', $customer->getLastname());
                }
                /** @var $customerForm Mage_Customer_Model_Form */
                $customerForm = $this->_getModel('customer/form');
                $customerForm->setFormCode('customer_account_edit')
                    ->setEntity($customer);

                $customerData = $customerForm->extractData($this->getRequest());

                $errors = array();
                $customerErrors = $customerForm->validateData($customerData);
                if ($customerErrors !== true) {
                    $errors = array_merge($customerErrors, $errors);
                } else {
                    $customerForm->compactData($customerData);
                    $errors = array();

                    // Validate account and compose list of errors if any
                    $customerErrors = $customer->validate();
                    if (is_array($customerErrors)) {
                        $errors = array_merge($errors, $customerErrors);
                    }
                }

                if (!empty($errors)) {
                    $result['status'] = 0;
                    $result['message'] = implode(', ', $errors);
                } else {
                    try {

                        $customer->cleanPasswordsValidationData();
                        $customer->setGender($post['gender']);
                        $customer->setWeight($post['weight']);
                        $customer->setTypeWeight($post['type_weight']);
                        $customer->setHeight($post['height']);
                        $customer->setUnitMeasurements($post['unit_measurements']);
                        $customer->setAvatarCustomer($post['avatar']);
                        $customer->setDob($post['dob']);
                        $customer->save();
                        $customer = Mage::getModel('customer/customer')->load($customer->getId());

                        $result['status'] = 1;
                        $result['message'] = 'Profile has been saved successful!';
                        $result['data'] = $customer->getData();
                        $result['data']['location'] = $customer->getLocation();
                        $result['data']['dob'] = $customer->getDob();
                    } catch (Mage_Core_Exception $e) {
                        $result['status'] = 0;
                        $result['message'] = $e->getMessage();
                    } catch (Exception $e) {
                        $result['status'] = 0;
                        $result['message'] = $e->getMessage();
                    }
                }

            } else {
                $result['status'] = 0;
                $result['message'] = 'Have not enough data to save';
            }
        } else {
            $result['status'] = 0;
            $result['message'] = 'Please login first';
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Login post action
     */
    public function loginPostAction()
    {
        $session = $this->_getSession();
        $result = ['code' => 200];

        if ($this->_getSession()->isLoggedIn()) {
            $token = $this->getToken($session->getCustomer());
            $customer = Mage::getModel('customer/customer')->load($session->getCustomer()->getId());
            $result['status'] = 1;
            $result['message'] = 'Login success';
            $result['token'] = $token;
            $result['data'] = $customer->getData();
            $result['data']['location'] = $customer->getLocation();
            $result['data']['dob'] = $customer->getDob();

        } elseif ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost();
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getId()) {
                        $customer = $session->getCustomer();
                        $token = $this->getToken($session->getCustomer());
                        $result['token'] = $token;
                        $result['status'] = 1;
                        $result['message'] = 'Login success';
                        $result['data'] = $session->getCustomer()->getData();
                        $result['data']['location'] = $session->getCustomer()->getLocation();
                        $result['data']['dob'] = $session->getCustomer()->getDob();

                    } else {
                        $result['token'] = null;
                        $result['status'] = 0;
                        $result['message'] = 'Invalid username or password';
                    }
                } catch (Mage_Core_Exception $e) {
                    $result['token'] = null;
                    $result['status'] = 0;
                    $result['message'] = $e->getMessage();
                } catch (Exception $e) {
                    $result['token'] = null;
                    $result['status'] = 0;
                    $result['message'] = $e->getMessage();
                }
            }
        }
        if(isset($result['data'])){
            $groupIsFactory = Mage::getStoreConfig('qsoft_appapi/group_setting/group_factory');
            $groupIsMerchant = Mage::getStoreConfig('qsoft_appapi/group_setting/group_merchant');
            $customerGroup = $customer->getGroupId();
            $role = 0;
            if($customerGroup == $groupIsFactory){
                $role = 1;
            }
            if($customerGroup == $groupIsMerchant){
                $role = 3;
                if($customer->getAllowToViewMeasurement()){
                    $role = 2;
                }
            }
            $result['data']['roles'] = $role;
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Create customer account action
     */
    public function createPostAction()
    {
        $result = ['code' => 200];
        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $token = $this->getToken($session->getCustomer());
            $result['status'] = 1;
            $result['token'] = $token;
            $result['message'] = 'Your account is registered successfully';
            $result['data'] = $session->getCustomer()->getData();
        } else {
            $customer = $this->_getCustomer();

            try {
                $errors = $this->_getCustomerErrors($customer);

                if (empty($errors)) {
                    $customer->cleanPasswordsValidationData();
                    $customer->save();
                    $this->_dispatchRegisterSuccess($customer);
                    $customer->setPassword($this->getRequest()->getPost('password'));
                    $this->_successProcessRegistration($customer);
                    $token = $this->getToken($customer);
                    $result['token'] = $token;
                    $result['status'] = 1;
                    $result['message'] = "Your account is registered successfully";
                    $result['data'] = $customer->getData();
                    $model = Mage::getModel('qsoft_appapi/token');
                    $dataSave = array(
                        'customer_id' => $customer->getId(),
                        'token' => $token
                    );
                    $model->setId(null)->addData($dataSave)->save();
                } else {
                    $result['token'] = null;
                    $result['status'] = 0;
                    $result['message'] = 'Please enter your registration information';
                }
            } catch (Mage_Core_Exception $e) {
                $session->setCustomerFormData($this->getRequest()->getPost());
                if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                    $result['token'] = null;
                    $result['status'] = 0;
                    $result['message'] = 'There is already an account with this email address. If you are sure that it is your email address, Please go to Login form to login your account';
                } else {
                    $result['token'] = null;
                    $result['status'] = 0;
                    $result['message'] = $this->_escapeHtml($e->getMessage());
                }
            } catch (Exception $e) {
                $result['token'] = null;
                $result['status'] = 0;
                $result['message'] = $this->__('Cannot save the customer.');
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Success Registration
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return Mage_Customer_AccountController
     */
    protected function _successProcessRegistration(Mage_Customer_Model_Customer $customer)
    {
        $session = $this->_getSession();
        if ($customer->isConfirmationRequired()) {
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store */
            $store = $app->getStore();
            $customer->sendNewAccountEmail(
                'confirmation',
                $session->getBeforeAuthUrl(),
                $store->getId()
            );
            $customerHelper = $this->_getHelper('customer');
            $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
                $customerHelper->getEmailConfirmationUrl($customer->getEmail())));
        } else {
            $this->_welcomeCustomer($customer);
        }
        return $this;
    }

    protected function _successProcessRegistrationProfile(Mage_Customer_Model_Customer $customer)
    {
        $session = $this->_getSession();
        if ($customer->isConfirmationRequired()) {
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store */
            $store = $app->getStore();
            $customer->sendNewAccountEmail(
                'confirmation',
                $session->getBeforeAuthUrl(),
                $store->getId()
            );
            $customerHelper = $this->_getHelper('customer');
            $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
                $customerHelper->getEmailConfirmationUrl($customer->getEmail())));
        } else {

            $this->_welcomeCustomer($customer);
        }
        return $this;
    }

    /**
     * Forgot password
     */
    public function forgotPasswordAction()
    {
        $params = Mage::app()->getRequest()->getParams();
        if (!empty($params)) {
            $email = $_REQUEST['email'];
            $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);
            if ($customer->getId()) {
                try {
                    $newResetPasswordLinkToken = Mage::helper('customer')->generateResetPasswordLinkToken();
                    $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                    $customer->save();
                    $customer->sendPasswordResetConfirmationEmail();
                    $result = array('code' => 200, 'action' => 'send', 'status' => 1, 'message' => $this->_getHelper('customer')
                        ->__('If there is an account associated with %s you will receive an email with a link to reset your password.',
                            $this->_getHelper('customer')->escapeHtml($email)));
                } catch (Exception $exception) {
                    $result = array('code' => 300, 'action' => 'failed', 'status' => 0, 'message' => $this->_escapeHtml($exception->getMessage()));
                }
            } else {
                $result = array('code' => 301, 'action' => 'failed', 'status' => 0, 'message' => 'Invalid email address.');
            }

        } else {
            $result = array('code' => 404, 'action' => 'failed', 'status' => 0, 'message' => 'Please enter your email.');
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    protected function getToken($customer)
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $token = $read->fetchOne('select token from qsoft_customer_token where customer_id=?', array($customer->getId()));
        if (!$token) {
            $token = Mage::helper('core')->getRandomString(20);

            $model = Mage::getModel('qsoft_appapi/token');
            $dataSave = array(
                'customer_id' => $customer->getId(),
                'token' => $token
            );
            $model->setId(null)->addData($dataSave)->save();
        }
        return $token;
    }


    public function createProfileAction()
    {
        $result = ['code' => 200];
        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();

        $customer = $this->_getCustomer();
        $password = Mage::helper('core')->getRandomString(10);
        $this->getRequest()->setPost('password', $password);
        $this->getRequest()->setPost('confirmation', $password);
        $post = $this->getRequest()->getPost();
        try {
            $errors = $this->_getCustomerErrors($customer);
            if (empty($errors)) {
                $customer->cleanPasswordsValidationData();
                $customer->setGender($post['gender']);
                $customer->setWeight($post['weight']);
                $customer->setTypeWeight($post['type_weight']);
                $customer->setHeight($post['height']);
                $customer->setUnitMeasurements($post['unit_measurements']);
                $customer->setAvatarCustomer($post['avatar']);
                $customer->save();
                $customer->setPassword($this->getRequest()->getPost('password'));
                $this->_dispatchRegisterSuccess($customer);
                $this->_successProcessRegistrationProfile($customer);
                $token = $this->getToken($customer);
                $result['status'] = 1;
                $result['message'] = "success";
                $result['data'] = $customer->getData();
                $model = Mage::getModel('qsoft_appapi/token');
                $dataSave = array(
                    'customer_id' => $customer->getId(),
                    'token' => $token
                );
                $model->setId(null)->addData($dataSave)->save();
                $currentToken = Mage::app()->getRequest()->getHeader('token');
                if ($currentCustomer = $this->getCustomerFromToken($currentToken)) {
                    $relationModel = Mage::getModel('qsoft_appapi/relationship');
                    $dataInsert = array('parent_id' => $currentCustomer, 'customer_id' => $customer->getId());
                    $relationModel->setId(null)
                        ->addData($dataInsert)
                        ->save();
                }
            } else {
                $result['status'] = 0;
                $result['message'] = implode(', ', $errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $result['status'] = 0;
                $result['message'] = 'There is already an account with this email address. If you are sure that it is your email address, Please go to Login form to login your account';
            } else {
                $result['status'] = 0;
                $result['message'] = $this->_escapeHtml($e->getMessage());
            }
        } catch (Exception $e) {
            $result['status'] = 0;
            $result['message'] = $this->__('Cannot save the customer.');
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    protected function getSocialCustomer($socialId)
    {
        $collection = Mage::getModel('customer/customer')->getCollection();
        $collection->addAttributeToFilter('social_token', $socialId);
        if ($collection->getSize()) {
            return Mage::getModel('customer/customer')->load($collection->getFirstItem()->getId());
        }
        return false;
    }

    public function socialLoginAction()
    {
        $data = Mage::helper('core')->jsonDecode($this->getRequest()->getPost('data'));
        $result = ['code' => 200];
        $socialCustomer = $this->getSocialCustomer($data['tokenId']);
        if ($socialCustomer) {
            $token = $this->getToken($socialCustomer);
            $result['token'] = $token;
            $result['status'] = 1;
            $result['message'] = "success";
            $result['data'] = $socialCustomer->getData();
            $result['data']['dob'] = $socialCustomer->getDob();
        } else {
            /** @var $session Mage_Customer_Model_Session */
            $customer = $this->_getCustomer();
            $password = Mage::helper('core')->getRandomString(10);
            $this->getRequest()->setPost('password', $password);
            $this->getRequest()->setPost('confirmation', $password);
            $this->getRequest()->setPost('firstname', $data['firstname']);
            $this->getRequest()->setPost('lastname', $data['lastname']);
            $this->getRequest()->setPost('email', $data['email']);

            try {
                $errors = $this->_getCustomerErrors($customer);
                if (empty($errors)) {
                    //{tokenId:"tokenId", email:"email", frstname:"frstname", lastname:"lastname", typeLogin:"typeLogin", avatar:"avatar"}
                    $customer->cleanPasswordsValidationData();
                    $customer->setSocialToken($data['tokenId']);
                    $customer->setSocialType($data['typeLogin']);
                    $customer->setAvatarCustomer($data['avatar']);
                    $customer->save();
                    $this->_dispatchRegisterSuccess($customer);
                    $this->_successProcessRegistrationProfile($customer);
                    $token = $this->getToken($customer);
                    $result['status'] = 1;
                    $result['message'] = "success";
                    $result['token'] = $token;
                    $result['data'] = $customer->getData();
                    $result['data']['dob'] = $customer->getDob();
                    $model = Mage::getModel('qsoft_appapi/token');
                    $dataSave = array(
                        'customer_id' => $customer->getId(),
                        'token' => $token
                    );
                    $model->setId(null)->addData($dataSave)->save();
                    $currentToken = Mage::app()->getRequest()->getHeader('token');
                    if ($currentCustomer = $this->getCustomerFromToken($currentToken)) {
                        $relationModel = Mage::getModel('qsoft_appapi/relationship');
                        $dataInsert = array('parent_id' => $currentCustomer, 'customer_id' => $customer->getId());
                        $relationModel->setId(null)
                            ->addData($dataInsert)
                            ->save();
                    }
                } else {
                    $result['status'] = 0;
                    $result['message'] = implode(', ', $errors);
                }
            } catch (Mage_Core_Exception $e) {
                if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                    $result['status'] = 0;
                    $result['message'] = 'There is already an account with this email address. If you are sure that it is your email address, Please go to Login form to login your account';
                } else {
                    $result['status'] = 0;
                    $result['message'] = $this->_escapeHtml($e->getMessage());
                }
            } catch (Exception $e) {
                $result['status'] = 0;
                $result['message'] = $this->__('Cannot save the customer.');
            }
        }


        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    protected function getCustomerFromToken($token)
    {
        $model = Mage::getModel('qsoft_appapi/token');

        $collection = $model->getCollection()
            ->addFieldToFilter('token', $token);

        if ($collection->getFirstItem()->getCustomerId()) {
            return $collection->getFirstItem()->getCustomerId();
        }

        return false;
    }

    public function logOutAction()
    {
        try {
            $this->_getSession()->logout()->renewSession();
            $result = array('code' => 200, 'status' => 1, 'message' => 'Logout Success');
        } catch (Mage_Core_Exception $e) {
            $result = array('code' => 300, 'action' => 'failed', 'status' => 0, 'message' => $this->_escapeHtml($e->getMessage()));
        } catch (Exception $e) {
            Mage::logException($e);
            $result = array('code' => 300, 'action' => 'failed', 'status' => 0, 'message' => 'Customer logout problem');
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
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

    public function changePasswordAction()
    {
        $validate = 0;
        $result = [];
        $token = '';

        foreach (getallheaders() as $key => $value) {
            if ($key == 'token') {
                $token = $value;
            }
        }
        if (!$token) {
            $result = array('code' => 401, 'message' => 'Login fail!');
        } else {
            $customerId = $this->checkToken($token);
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $data = $customer->getData();
            $username = $data['email'];
            $passwordOld = $_REQUEST['password_old'];
            $passwordNew = $_REQUEST['password_new'];
            $websiteId = $customer->getWebsiteId();
            try {
                $login_customer_result = Mage::getModel('customer/customer')->setWebsiteId($websiteId)->authenticate($username, $passwordOld);
                $validate = 1;
            } catch (Exception $ex) {
                $validate = 0;
            }
            if ($validate == 1) {
                try {
                    $customer = Mage::getModel('customer/customer')->load($customerId);
                    $customer->setPassword($passwordNew);
                    $customer->save();
                    $result = array('code' => 200, 'status' => 1, 'message' => 'Your Password has been Changed Successfully');
                } catch (Exception $ex) {
                    $result = array('code' => 301, 'status' => 0, 'message' => 'Error : ' . $ex->getMessage());
                }
            } else {
                $result = array('code' => 401, 'status' => 0, 'message' => 'Incorrect Old Password.');
            }

        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function saveMeasurementAction()
    {
        $result = [];
        $token = '';

        foreach (getallheaders() as $key => $value) {
            if ($key == 'token') {
                $token = $value;
            }
        }
        if (!$token) {
            $result = array('code' => 401, 'status' => 0, 'message' => 'Login fail!');
        } else {
            $customerId = $this->checkToken($token);
            if (isset($_REQUEST['customerId']) && $_REQUEST['customerId']) {
                $customerId = $_REQUEST['customerId'];
            }
            $data = $this->getRequest()->getPost();

            $currentDate = Mage::getModel('core/date')->date('Y-m-d H:i:s');

            $measure = array(
                '1' => $data['height'],
                '2' => $data['weight'],
                '3' => $data['body_lenght'],
                '4' => $data['chest'],
                '5' => $data['neck'],
                '6' => $data['waist'],
                '7' => $data['paint_waist'],
                '8' => $data['hip'],
                '9' => $data['high_thigh'],
                '10' => $data['low_thigh'],
                '11' => $data['thigh_lenght'],
                '12' => $data['waist_to_thigh'],
                '13' => $data['chest_to_legs'],
                '14' => $data['bicep'],
                '15' => $data['front_rise'],
                '16' => $data['shoulder_back'],
                '17' => $data['back_rise'],
                '18' => $data['armscye']
            );
            $measureValues = json_encode($measure);

            if ($data['unit'] == 1) {
                $unit = array(
                    "weight" => '1',
                    "height" => '1'
                );
                $unitValue = json_encode($unit);
                // calculation Metric BMI formula BMI = weight (kg) ÷ height2 (m2)
                $height = $data['height'] / 100;
                $metric = $data['weight'] / ($height * $height);

                // Imperial (USA) BMI Formula BMI = weight (lb) ÷ height2 (in2) × 703
                $weight = $data['weight'] * 2.026;
                $height = $data['height'] * 0.3937;
                $imperial = $weight / ($height * $height) * 703;


            } else {
                $unit = array(
                    "weight" => '2',
                    "height" => '2'
                );
                $unitValue = json_encode($unit);

                $imperial = $data['weight'] / ($data['height'] * $data['height']) * 703;
                // metric
                $weight = $data['weight'] * 0.4536;
                $height = $data['height'] * 0.0254;
                $metric = $weight / ($height * $height);
            }

            // body mass "1. For men: LBM = (0.32810 × W) + (0.33929 × H) − 29.5336 2. For women: LBM = (0.29569 × W) + (0.41813 × H) − 43.2933"
            if ($data['gender'] == 1) {
                $lbm = 0.32810 * $data['weight'] + 0.33929 * $data['height'] - 29.5336;
            } else {
                $lbm = 0.29569 * $data['weight'] + 0.41813 * $data['height'] - 43.2933;
            }

            $sex = 0;
            //Body weight
            if ($data['gender'] == 1) {
                $sex = 1;
                $metricBodyWeight = 50 + 0.9 * ($data['height'] - 152);
                $imperialBodyWeight = 110 + 2 * ($data['height'] - 152);
            } else {
                $metricBodyWeight = 45.5 + 0.9 * ($data['height'] - 152);
                $imperialBodyWeight = 100 + 2 * ($data['height'] - 152);
            }

            // body fat
            $metricBodyFat = 1.20 * $metric + 0.23 * $data['age'] - 10.8 * $sex;
            $imperialBodyFat = 1.20 * $imperial + 0.23 * $data['age'] - 10.8 * $sex;

            $data = array(
                'unit' => $unitValue,
                'customer_id' => $customerId,
                'measures' => $measureValues,
                'owner_scan' => $this->checkToken($token),
                'body_scan' => $data['body_scan'],
                'updated_at' => $currentDate,
                'csv' => $data['csv'],
                'thumbnail_customer' => $data['thumbnail_customer'],
                'mesh_customer' => $data['mesh_customer'],
                'age' => $data['age'],
                'gender' => $data['gender'],
                'height' => $data['height'],
                'weight' => $data['weight'],
                'bmi' => Mage::helper('core')->jsonEncode(array('metric' => $metric, 'imperial' => $imperial)),
                'body_weight' => Mage::helper('core')->jsonEncode(array('metric' => $metricBodyWeight, 'imperial' => $imperialBodyWeight)),
                'body_fat' => Mage::helper('core')->jsonEncode(array('metric' => $metricBodyFat, 'imperial' => $imperialBodyFat)),
                'lean_mass' => $lbm,
                'scan_method' => $data['scan_method'],
            );

            $modelCustomer = Mage::getModel('qsoft_customermeasure/value');
            try {
                $modelCustomer->setData($data);
                $modelCustomer->save();
                $result = array('code' => 200, 'status' => 1, 'message' => 'Save success');
            } catch (Exception $ex) {
                $result = array('code' => 401, 'status' => 0, 'message' => $this->_escapeHtml($ex->getMessage()));
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }


    protected function checkAuth($token)
    {
        $model = Mage::getModel('qsoft_appapi/token');

        $collection = $model->getCollection()
            ->addFieldToFilter('token', $token);

        if ($collection->getFirstItem()->getCustomerId()) {
            $customer = Mage::getModel('customer/customer')->load($collection->getFirstItem()->getCustomerId());
            $data = $customer->getData();

//            if ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_factory') && $data['group_id'] || Mage::getStoreConfig('qsoft_appapi/group_setting/group_merchant')) {
            if ($data['group_id'] == Mage::getStoreConfig('qsoft_appapi/group_setting/group_factory') && $data['group_id']) {
                return true;
            }
        }

        return false;
    }

    public function createNewMeasureAction()
    {
        $result = [];
        $token = '';

        foreach (getallheaders() as $key => $value) {
            if ($key == 'token') {
                $token = $value;
            }
        }
        if (!$token) {
            $result = array('code' => 401, 'status' => 0, 'message' => 'Login fail!');
        } else {
            if ($this->checkAuth($token)) {
                $data = $this->getRequest()->getPost();
                $data = array(
                    'title' => $data['title'],
                    'video_url' => $data['video_url'],
                    'description' => $data['description'],
                    'unit' => $data['unit'],
                    'gender' => $data['gender'],
                    'min_value' => $data['min_value'],
                    'max_value' => $data['max_value'],
                    'show_in_dashboard' => $data['show_in_dashboard'],
                    'type_of_measurement' => $data['type_of_measurement'],
                );
                $modelMeasure = Mage::getModel('qsoft_customermeasure/type');
                try {
                    $modelMeasure->setData($data);
                    $modelMeasure->save();
                    $result = array('code' => 200, 'status' => 1, 'message' => 'Save success');
                } catch (Exception $ex) {
                    $result = array('code' => 401, 'status' => 0, 'message' => $this->_escapeHtml($ex->getMessage()));
                }
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}