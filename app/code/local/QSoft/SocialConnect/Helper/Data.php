<?php
/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 10/27/2015
 * Time: 4:49 PM
 */ 
class QSoft_SocialConnect_Helper_Data extends Mage_Core_Helper_Abstract {
    public static function log($message, $level = null, $file = '', $forceLog = false)
    {
        if(Mage::getIsDeveloperMode()) {
            Mage::log($message, $level, $file, $forceLog);
        }
    }
}