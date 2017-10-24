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
 * Testimonial list block
 *
 * @category    Evince
 * @package        Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Block_Testimonial_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $testimonials = Mage::getResourceModel('testimony/testimonial_collection')
            ->addFilter('status', 1);

        if(Mage::helper('testimony')->getLimitTestimonial(Mage::app()->getStore()->getId())) {
            $testimonials->addLimitation(Mage::helper('testimony')->getLimitTestimonial(Mage::app()->getStore()->getId()));
        }

        $testimonials->setOrder('display_order', 'asc');
        $this->setTestimonials($testimonials);
    }
}