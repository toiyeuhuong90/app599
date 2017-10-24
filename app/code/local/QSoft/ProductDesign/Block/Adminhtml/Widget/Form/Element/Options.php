<?php

/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 10/14/2015
 * Time: 2:12 PM
 */
class QSoft_ProductDesign_Block_Adminhtml_Widget_Form_Element_Options extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface
{

    protected $_element;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('qsoft/productoptions/widget/form/element/images.phtml');
    }

    public function getElement()
    {
        return $this->_element;
    }

    public function setElement(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_element = $element;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    public function getAddButtonHtml()
    {
        return $this->getChildHtml('addBtn');
    }

    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delBtn');
    }

    public function getBrowserFieldHtml()
    {
        return $this->getChildHtml('browser');
    }

    protected function _prepareLayout()
    {
        $addBtn = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'label' => Mage::helper("productdesign")->__('Add Option'),
            'onclick' => 'window.catalogSlider.add()',
            'class' => 'add'
        ));
        $this->setChild('addBtn', $addBtn);

        $delBtn = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'onclick' => 'window.catalogSlider.remove({{id}})',
            'class' => 'delete'
        ));
        $this->setChild('delBtn', $delBtn);

        parent::_prepareLayout();
    }

    public function getItems()
    {
        $items = array();
        $data = $this->getData('options');

        $i = 0;
        if ($data) {
            foreach ($data as $item) {
                $items[$i]['id'] = $item->getId();

                $items[$i]['name'] = str_replace(array("\r", "\n"), '', $item->getName());

                $items[$i]['description'] = str_replace(array("\r", "\n"), '', $item->getDescription());
                $items[$i]['classhtml'] = $item->getClasshtml();
                $items[$i]['url_key'] = $item->getUrlKey();
                $items[$i]['price'] = $item->getPrice();
                $items[$i]['icon'] = $item->getIcon();
                $items[$i]['image_front'] = $item->getImageFront();
                $items[$i]['image_back'] = $item->getImageBack();
                $items[$i]['position'] = $item->getPosition();
                $checked = '';
                if ($item->getIsDefault()) {
                    $checked = ' checked="checked" ';
                }
                $items[$i]['checked'] = $checked;
                $i++;
            }
        }

        return $items;
    }

    /**
     * Retrieve stores collection with default store
     *
     * @return Mage_Core_Model_Mysql4_Store_Collection
     */
    public function getStores()
    {
        $stores = $this->getData('stores');
        if (is_null($stores)) {
            $stores = Mage::getModel('core/store')
                ->getResourceCollection()
                ->setLoadDefault(true)
                ->load();
            $this->setData('stores', $stores);
        }
        return $stores;
    }
}