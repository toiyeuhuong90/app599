<?php
/**
 * Evince_Testimony extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category    Evince
 * @package        Evince_Testimony
 * @copyright    Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Testimonial resource model
 *
 * @category    Evince
 * @package        Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Model_Resource_Testimonial extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        $this->_init('testimony/testimonial', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     * @access public
     * @param int $testimonialId
     * @return array
     * @author Ultimate Module Creator
     */
    public function lookupStoreIds($testimonialId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('testimony/testimonial_store'), 'store_id')
            ->where('testimonial_id = ?', (int)$testimonialId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Evince_Testimony_Model_Resource_Testimonial
     * @author Ultimate Module Creator
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Evince_Testimony_Model_Testimonial $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('testimony_testimonial_store' => $this->getTable('testimony/testimonial_store')),
                $this->getMainTable() . '.entity_id = testimony_testimonial_store.testimonial_id',
                array()
            )
                ->where('testimony_testimonial_store.store_id IN (?)', $storeIds)
                ->order('testimony_testimonial_store.store_id DESC')
                ->limit(1);
        }
        return $select;
    }

    /**
     * Assign testimonial to store views
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Evince_Testimony_Model_Resource_Testimonial
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table = $this->getTable('testimony/testimonial_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'testimonial_id = ?' => (int)$object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'testimonial_id' => (int)$object->getId(),
                    'store_id' => (int)$storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
}