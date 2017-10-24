<?php
/**
 * Magegiant
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the magegiant.com license that is
 * available through the world-wide-web at this URL:
 * http://magegiant.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magegiant
 * @package     Magegiant_LinkedInLogin
 * @copyright   Copyright (c) 2014 Magegiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * LinkedInLogin Index Controller
 *
 * @category    Magegiant
 * @package     Magegiant_LinkedInLogin
 * @author      Magegiant Developer
 */
class Magegiant_LinkedInLogin_IndexController extends Mage_Core_Controller_Front_Action
{
	protected function _getHelper()
	{
		return Mage::helper('linkedinlogin');
	}

	protected function _getSession()
	{
		return Mage::getSingleton('core/session');
	}

	public function loginAction()
	{

		$config['base_url']        = Mage::getBaseUrl() . 'linkedinlogin/index/login';
		$config['callback_url']    = Mage::getBaseUrl() . 'linkedinlogin/index/callback';
		$config['linkedin_access'] = $this->_getHelper()->getLinkedInConfig('api_key');
		$config['linkedin_secret'] = $this->_getHelper()->getLinkedInConfig('client_key');
		if(empty($config['linkedin_access']))
		{
			Mage::getSingleton('core/session')->addError('Please specify API key');
			die("<script type=\"text/javascript\">try{window.opener.location.reload(true);}catch(e){window.opener.location.href=\"".Mage::getBaseUrl()."\"} window.close();</script>");

		}else if(empty($config['linkedin_secret']))
		{
			Mage::getSingleton('core/session')->addError('Please specify SECRET key');
			die("<script type=\"text/javascript\">try{window.opener.location.reload(true);}catch(e){window.opener.location.href=\"".Mage::getBaseUrl()."\"} window.close();</script>");
		}

		$linkedin = Mage::helper('linkedinlogin/linkedin');
		$linkedin->setParams($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url']);

		$linkedin->getRequestToken();
		$this->_getSession()->setRequestToken(serialize($linkedin->request_token));
		$this->_redirectUrl($linkedin->generateAuthorizeUrl());
		return $this;
	}

	protected function _appendJs($string)
	{
		$this->loadLayout();
		$layout = Mage::app()->getLayout();
		$block  = $layout->createBlock('core/text');
		$block->setText(
			$string
		);
		$this->getLayout()->getBlock('head')->append($block);
		$this->renderLayout();
	}

	public function callbackAction()
	{
		$config['base_url']        = Mage::getBaseUrl() . 'linkedinlogin/index/login';
		$config['callback_url']    = Mage::getBaseUrl() . 'linkedinlogin/index/callback';
		$config['linkedin_access'] = $this->_getHelper()->getLinkedInConfig('api_key');
		$config['linkedin_secret'] = $this->_getHelper()->getLinkedInConfig('client_key');

		$linkedin = Mage::helper('linkedinlogin/linkedin');
		$linkedin->setParams($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url']);

		if ($this->getRequest()->getParam('oauth_verifier')) {
			$oauthVerifier = $this->getRequest()->getParam('oauth_verifier');
			$this->_getSession()->setOauthVerifier($oauthVerifier);

			$linkedin->request_token  = unserialize($this->_getSession()->getRequestToken());
			$linkedin->oauth_verifier = $this->_getSession()->getOauthVerifier();
			$linkedin->getAccessToken($oauthVerifier);
			$this->_getSession()->setOauthAccessToken(serialize($linkedin->access_token));
			$this->_redirectUrl($config['callback_url']);
			return;
		} else {
			$linkedin->request_token  = unserialize($this->_getSession()->getRequestToken());
			$linkedin->oauth_verifier = $this->_getSession()->getOauthVerifier();
			$linkedin->access_token   = unserialize($this->_getSession()->getOauthAccessToken());
		}
		$xml_response = $linkedin->getProfile("~:(id,first-name,last-name)");
		$xmlToObject = simplexml_load_string($xml_response);
		$json = json_encode($xmlToObject);
		$data = json_decode($json,TRUE);
		$xml_responseEmail = $linkedin->getProfile("~:(email-address)");
		$xmlToObjectEmail = simplexml_load_string($xml_responseEmail);
		$jsonEmail = json_encode($xmlToObjectEmail);
		$dataEmail = json_decode($jsonEmail,TRUE);
		$store_id   = Mage::app()->getStore()->getStoreId(); //add
		$website_id = Mage::app()->getStore()->getWebsiteId(); //add
		$customer = Mage::getModel('customer/customer')->setId(null);
		$randomString = $this->_getHelper()->generateRandomString();
		$customer->setFirstname($data['first-name'])
			->setLastname($data['last-name'])
			->setEmail((isset($dataEmail) && !empty($dataEmail['email']))?$dataEmail['email']:"demo.".$randomString."@linkedin.com")
			->setWebsiteId($website_id)
			->setStoreId($store_id)
			->save();
		Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
		if(isset($dataEmail) && empty($dataEmail['email']))
		{
			$this->_appendJs("<script type=\"text/javascript\">try{window.opener.location.href=\"" . Mage::getUrl('customer/account/edit/') . "\"}catch(e){window.opener.location.href=\"" . Mage::getBaseUrl() . "\"} window.close();</script>");
			Mage::getSingleton('core/session')->addNotice('Please update your email.');
			return;

		}else
		{
			$this->_appendJs("<script type=\"text/javascript\">try{window.opener.location.reload(true);}catch(e){window.opener.location.href=\"" . Mage::getBaseUrl() . "\"} window.close();</script>");
		}
		return;

	}
}