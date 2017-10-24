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
 * Testimony default helper
 *
 * @category    Evince
 * @package        Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Helper_Data extends Mage_Core_Helper_Abstract
{

    CONST XML_PATH_TESTIMONIAL_LIMIT = 'testimony/testimonial/limit';

    /**
     * get the url to the testimonial list page
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTestimonialsUrl()
    {
        return Mage::getUrl('testimony/testimonial/index');
    }


    /**
     * Files Helper
     *
     * @return Evince_Testimony_Helper_Files
     */
    public function getFiles()
    {
        return Mage::helper('testimony/files');
    }

    /**
     * Get Limit Number of Testimonial
     * @param $storeId
     *
     * @return integer
     */
    public function getLimitTestimonial($storeId = null){
        return Mage::getStoreConfig(self::XML_PATH_TESTIMONIAL_LIMIT, $storeId);
    }

    
}