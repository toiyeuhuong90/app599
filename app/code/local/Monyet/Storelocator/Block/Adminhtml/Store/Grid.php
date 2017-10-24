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
 
class Monyet_Storelocator_Block_Adminhtml_Store_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('storeGrid');
      $this->setDefaultSort('store_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('storelocator/store')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('store_id', array(
          'header'    => Mage::helper('storelocator')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'store_id',
      ));

      $this->addColumn('store_name', array(
          'header'    => Mage::helper('storelocator')->__('Store Name'),
          'align'     =>'left',
          'index'     => 'store_name',
      ));
	  
      $this->addColumn('country', array(
          'header'    => Mage::helper('storelocator')->__('Country'),
          'align'     => 'left',
          'index'     => 'country',
          'type'      => 'options',
          'options'   => Mage::helper('storelocator')->getListCountry(),
      ));		  

		  $this->addColumn('state', array(
			  'header'    => Mage::helper('storelocator')->__('State'),
			  'align'     => 'left',
			  'index'     => 'state',
		  ));	  
	  
      $this->addColumn('city', array(
          'header'    => Mage::helper('storelocator')->__('City'),
          'align'     => 'left',
          'index'     => 'city',
      ));
	 

      $this->addColumn('store_latitude', array(
          'header'    => Mage::helper('storelocator')->__('Latitude'),
          'align'     =>'left',
		  'width'     => '50px',
          'index'     => 'store_latitude',
      ));

      $this->addColumn('store_longitude', array(
          'header'    => Mage::helper('storelocator')->__('Longitude'),
          'align'     =>'left',
		  'width'     => '50px',
          'index'     => 'store_longitude',
      ));	  

      $this->addColumn('status', array(
          'header'    => Mage::helper('storelocator')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('storelocator')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('storelocator')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('storelocator')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('storelocator')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('storelocator_id');
        $this->getMassactionBlock()->setFormFieldName('storelocator');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('storelocator')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('storelocator')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('storelocator/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('storelocator')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('storelocator')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}