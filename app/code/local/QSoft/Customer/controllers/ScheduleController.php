<?php
class QSoft_Customer_ScheduleController extends Mage_Core_Controller_Front_Action
{

    const SCHEDULE_XML_PATH_EMAIL_RECIPIENT  = 'qsoftcustomer/schedule/recipient_email';
    const SCHEDULE_XML_PATH_EMAIL_SENDER     = 'qsoftcustomer/schedule/sender_email';
    const SCHEDULE_XML_PATH_EMAIL_TEMPLATE_ADMIN   = 'qsoftcustomer/schedule/email_template_for_admin';
    const SCHEDULE_XML_PATH_EMAIL_TEMPLATE_CUSTOMER   = 'qsoftcustomer/schedule/email_template_for_customer';
    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * fittingAction: display when customer want to schedule a fitting
     *
     * @return page layout
     */
    public function indexAction(){
        $_SESSION['url_back']= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        /*$customerId = Mage::getSingleton('customer/session')->getId();
        if (isset($customerId)) {*/
            $this->loadLayout();
            $this->_initLayoutMessages('customer/session');
            $this->_initLayoutMessages('catalog/session');
            $this->renderLayout();
        /*} else {
            $this->_redirect('customer/account/login/');
        }*/
    }

    /**
     * fittingPostAction: process form schedule fitting and send a email to admin
     *
     * @return re-direct to fittingAction
     */
    public function indexPostAction(){
        $session = $this->_getSession();
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*');
            return;
        }
        if($dataPost = $this->getRequest()->getPost()){
            $customer = $session->getCustomer();
            $dataPost['customer_id'] = $customer->getId();
            $dataPost['created_at'] = date('Y-m-d h:i:s');

            $dataPost['interested'] = Mage::helper('core')->jsonEncode($dataPost['checkSchedule']);
            $dataPost['interested_in'] = implode($dataPost['checkSchedule']);

            $model = Mage::getModel("qsoft_customer/schedulebodyscan")
                ->addData($dataPost)
                ->setId($this->getRequest()->getParam("id"))
                ->save();
            $session->addSuccess($this->__('You have successfully sent a request to Escap Velocity to schedule a Body Scan appointment. Our team will get in touch with you soon to arrange a fitting visit. Thank you!'));

            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($dataPost);


                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                        Mage::getStoreConfig(self::SCHEDULE_XML_PATH_EMAIL_TEMPLATE_ADMIN),
                        Mage::getStoreConfig(self::SCHEDULE_XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::SCHEDULE_XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );

                //send mail for customer

                $postObject = new Varien_Object();
                $postObject->setData($dataPost);


                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                        Mage::getStoreConfig(self::SCHEDULE_XML_PATH_EMAIL_TEMPLATE_CUSTOMER),
                        Mage::getStoreConfig(self::SCHEDULE_XML_PATH_EMAIL_SENDER),
                        $dataPost['email'],
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__($e.'Unable to submit your request. Please, try again later'));
                $this->_redirect('*/*');
                return;
            }
        }
        $this->_redirect('*/*');
    }
}