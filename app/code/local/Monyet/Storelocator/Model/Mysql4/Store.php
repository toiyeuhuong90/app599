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
 
class Monyet_Storelocator_Model_Mysql4_Store extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('storelocator/store', 'store_id');
    }
	 
	protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('localstore_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('store_store'), $condition);
        
        foreach ((array) $object->getData('stores') as $store) {
            $storeArray = array ();
            $storeArray['localstore_id'] = $object->getId();
            $storeArray['store_ids'] = $store;

            $this->_getWriteAdapter()->insert(
                $this->getTable('store_store'), $storeArray
            );
        }
        
        return parent::_afterSave($object);
    }
	
	protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()->from(
            $this->getTable('store_store')
        )->where('localstore_id = ?', $object->getId());
        
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array ();
            foreach ($data as $row) {
                $storesArray[] = $row['store_ids'];
            }
            $object->setData('store_ids', $storesArray);
        }
        
        return parent::_afterLoad($object);
    }
}