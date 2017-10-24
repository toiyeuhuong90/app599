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
 
class Monyet_Storelocator_Model_Mysql4_Store_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('storelocator/store');
    }
	
	public function addStoreFilter($store)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array (
                 $store->getId()
            );
        }
        
        $this->getSelect()->join(
            array('store_table' => $this->getTable('store_store')),
            'main_table.store_id = store_table.localstore_id',
            array ()
        )->where('store_table.store_ids in (?)', array (
            0, 
            $store
        ))->group('main_table.store_id');
        
        return $this;
    }

}