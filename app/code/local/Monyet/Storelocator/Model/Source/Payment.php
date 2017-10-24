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
 
class Monyet_Storelocator_Model_Source_Payment
{
    public function toOptionArray()
	{
		$collection = Mage::getModel('payment/config')->getActiveMethods();
		
		if(! count($collection))
			return;
			
		$options = array();	
			
		foreach($collection as $item)
		{
			$title = $item->getTitle() ? $item->getTitle() : $item->getId();
			$options[] = array('value'=> $item->getId(), 'label' => $title);
		}
		
		return $options;
	}
}