<?php

class QSoft_CustomerMeasure_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $_customer
     * @return string
     */
    public function getCustomerFullName($_customer)
    {
        /* @var $_customer Mage_Customer_Model_Customer */
        return $_customer->getFirstname() . ' ' . $_customer->getLastname();
    }


    /**
     * @param $_toggle_data
     * @return array
     */
    public function splitToggleOption($_toggle_data)
    {
        $toggle = [];

        $items = explode('-', $_toggle_data);

        foreach ($items as $item=>$i) {
            $toggle[$i]['label'] = $i;
            $toggle[$i]['value'] = $i;
        }

        return $toggle;

    }


    /**
     * @param $_customer_id
     * @return QSoft_CustomerMeasure_Model_Resource_Type_Collection
     */
    public function getMeasures($_customer_id)
    {
        $_customer = $this->getCustomer($_customer_id);
        $collection = Mage::getModel('qsoft_customermeasure/type')->getCollection();

        foreach ($collection as $type) {

            $measure_value = $this->getMeasureValue($type->getData('measure_id'), $_customer);
            $type->setData('measure_value', $measure_value);
        }

        return $collection;
    }

    /**
     * @param $_customer Mage_Customer_Model_Customer
     * @param $_measure_id
     * @return Varien_Object
     */
    public function getMeasureValue($_measure_id, $_customer)
    {
        $value = Mage::getModel('qsoft_customermeasure/value')->getCollection()
            ->addFieldToFilter('measure_id', $_measure_id)
            ->addFieldToFilter('entity_id', $_customer->getId())
            ->getFirstItem();


        return $value->getData('measure_value');
    }

    /**
     * @param $customer_id
     * @return Mage_Customer_Model_Customer
     */

    public function getCustomer($customer_id)
    {
        return Mage::getModel('customer/customer')->load($customer_id);
    }

    /**
     * @param $customerId
     * @param $pdfFile path to file file pdf
     * @param $pathImage path to folder saved images
     * @return array images
     */

    public function saveCustomerBodyScan($customerId, $pdfFile, $pathImage,$time){
        $pdf = new QSoft_Imagick_PDF($pdfFile);
        $pages = $pdf->getNumberOfPages();
        $result = array();
        for($i=1; $i<=$pages; $i++){
            $pdf->setResolution(72)->setOutputFormat('png')->setPage($i)
                ->saveImage($pathImage . 'image' . $i . '.png');
            $result[] = str_replace('index.php/', '', Mage::getUrl().'media/bodyscan/'.$customerId.DS.$time.'/images/'.'image'.$i.'.png');
        }
        return $result;
    }

    public function getVideoThumb($url)
    {
        $id = $this->_getYoutubeVideoId($url);
        return 'http://img.youtube.com/vi/' . $id . '/default.jpg';
    }

    public function getVideoImage($url)
    {
        $id = $this->_getYoutubeVideoId($url);
        return 'http://img.youtube.com/vi/' . $id . '/maxresdefault.jpg';
    }

    public function getVideoEmbedLink($url)
    {
        $id = $this->_getYoutubeVideoId($url);
        return 'https://www.youtube.com/embed/' . $id . '?autoplay=1';
    }

    protected function _getYoutubeVideoId($url)
    {
        if (!$url) {

            return null;
        }

        parse_str(parse_url($url, PHP_URL_QUERY), $params);

        return isset($params['v']) ? $params['v'] : '';
    }
}