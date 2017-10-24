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
 
class Monyet_Storelocator_Block_Links extends Mage_Core_Block_Template
{
	public function addTopLinkStores()
	{
		$storeID = Mage::app()->getStore()->getId();
		if(Mage::getStoreConfig('general/storelocator/display_store_url',$storeID)==2) {
			$toplinkBlock = $this->getParentBlock();
			if($toplinkBlock)
			$toplinkBlock->addLink(Mage::getStoreConfig('general/storelocator/link_title',$storeID),'storelocator/index',Mage::getStoreConfig('general/storelocator/link_title',$storeID),true,array(),10);
		}
	}
	
	public function addFooterLinkStores()
	{
		$storeID = Mage::app()->getStore()->getId();
		if(Mage::getStoreConfig('general/storelocator/display_store_url',$storeID)==1) {
			$footerBlock = $this->getParentBlock();
			if($footerBlock)
			$footerBlock->addLink(Mage::getStoreConfig('general/storelocator/link_title',$storeID),'storelocator/index/index',Mage::getStoreConfig('general/storelocator/link_title',$storeID),true,array());
		}
	}
}

?>