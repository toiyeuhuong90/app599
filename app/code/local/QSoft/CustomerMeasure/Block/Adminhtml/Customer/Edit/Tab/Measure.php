<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/3/2016
 * Time: 4:05 PM
 */
class QSoft_CustomerMeasure_Block_Adminhtml_Customer_Edit_Tab_Measure extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setTemplate('qsoft/customer/measure/history.phtml');

    }

   public function getMeasures(){
       $customer = Mage::registry('current_customer');
       $collection = Mage::getModel('qsoft_customermeasure/type')->getCollection();
       $collection->addFieldToFilter('gender', array('in'=>array(0, $customer->getGender())));
       return $collection;
   }

    public function getMeasureValues($id){
        $valueCollection = Mage::getModel('qsoft_customermeasure/value')->getCollection();
        $valueCollection->addFieldToFilter('customer_id', $customer->getId());
        return $valueCollection->getFirstItem();
    }

    public function filter($value)
    {
        return number_format($value, 2);
    }

    protected function _prepareCollection(){
        $customer = Mage::registry('current_customer');
        $collection = Mage::getModel('qsoft_customermeasure/value')->getCollection();
        $collection->addFieldToFilter('customer_id', $customer->getId())
        ->setOrder('updated_at','desc');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('customer')->__('ID #'),
            'width'     => '100',
            'index'     => 'id',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('customer')->__('Created At'),
            'index'     => 'updated_at',
            'type'      => 'datetime',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('owner_scan', array(
            'header'    => Mage::helper('customer')->__('Owner scan'),
            'index'     => 'owner_scan',
            'type'      => 'options',
            'filter' => false,
            'sortable' => false,
            'options'   =>  $this->getCustomers(),
        ));

        $this->addColumn('action', array(
            'header'    => ' ',
            'filter'    => false,
            'sortable'  => false,
            'width'     => '100px',
            'renderer'  => 'qsoft_customermeasure/adminhtml_measurement_type_renderer_action'
        ));

        return parent::_prepareColumns();
    }

    protected function getCustomers() {
        $sql = 'SELECT f.entity_id as id, concat(f.value,\' \',l.value) as name FROM `customer_entity_varchar` as f left join customer_entity_varchar as l on f.entity_id=l.entity_id where f.attribute_id=5 and l.attribute_id=7';
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $customers = $read->fetchAll($sql);
        $result = array();
        foreach ($customers as $item) {
            $result[$item['id']] = $item['name'];
        }
        return $result;
    }

    public function getRowUrl($row)
    {
        return 'javascript:viewHistory('.$row->getId().');;';
    }

    public function getGridUrl()
    {
        return 'javascript:void(0);';
    }
}