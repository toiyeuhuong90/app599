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
 * Testimonial image helper
 *
 * @category    Evince
 * @package        Evince_Testimony
 * @author Ultimate Module Creator
 */
class Evince_Testimony_Helper_Testimonial_Image extends Evince_Testimony_Helper_Image_Abstract
{
    /**
     * image placeholder
     * @var string
     */
    protected $_placeholder = 'images/placeholder/testimonial.jpg';
    /**
     * image subdir
     * @var string
     */
    protected $_subdir = 'testimonial';


    /**
     * @param $image_url
     * @return string
     */
    public function getTestimonialImagePath($image_url)
    {
        if($image_url) {
            return 'testimonial' . DS . 'image' . str_replace('/', DS , $image_url);
        }
        
        return null;
    }

}