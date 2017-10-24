<?php
/**
 *Storelocator Extension
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://store.monyet.com/license.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to admin@monyet.com so we can mail you a copy immediately.
 *
 * @category   Magento Extensions
 * @package    Monyet_Storelocator
 * @author     Monyet <sales@monyet.com>
 * @copyright  2007-2011 Monyet
 * @license    http://store.monyet.com/license.txt
 * @version    1.0.1
 * @link       http://store.monyet.com
 */
 
class Monyet_Storelocator_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {	
		$this->loadLayout();  
		if($head = $this->getLayout()->getBlock('head'))
            $head->setTitle(Mage::getStoreConfig('general/storelocator/store_title'));
		$this->renderLayout();
    }
	public function updateStoreAction()
	{

		if($id = $this->getRequest()->getParam('store_id')) {
			Mage::getSingleton('checkout/session')->setData('storelocator_store', $id);
		} 
		if($is_storelocator = $this->getRequest()->getParam('is_storelocator')) {
			Mage::getSingleton('checkout/session')->setData('is_storelocator', $is_storelocator);
		} 
		$local_session = array(
							'store_id'=>Mage::getSingleton('checkout/session')->getData('storelocator_store'),
							'is_storelocator'=>Mage::getSingleton('checkout/session')->getData('is_storelocator'),
							);
		Mage::getSingleton('checkout/session')->setData('storelocator_session', $local_session);
	}
   
}