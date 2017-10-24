<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';

/**
 * Magento Compiler Shell Script
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Shell_Compiler extends Mage_Shell_Abstract
{

    public function run()
    {
        $helper = Mage::helper('core');
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $province = file_get_contents('http://api.vtp.vn/api/province');
        $cities = $helper->jsonDecode($province);
        
        foreach($cities['_embedded']['province'] as $city){
            $code = Mage::getSingleton('catalog/product_url')->formatUrlKey($city['Name']);
            $sql = 'insert into directory_country_region (country_id,code,default_name) values(?,?,?)';
            $write->query($sql, array('VN', $code, $city['Name']));
            $stateId = $write->lastInsertId();

            //province
            $sql = 'insert into viettelpost_province (state_id,vt_province_id,name,position) values(?,?,?,?)';
            $write->query($sql, array($stateId, $city['ProvinceId'], $city['Name'], 0));
            $cityId = $write->lastInsertId();

            //district
            $pdistrictData = file_get_contents('http://api.vtp.vn/api/district?province='.$city['ProvinceId']);
            $districts = $helper->jsonDecode($pdistrictData);
            foreach ($districts['_embedded']['district'] as $district){
                $sql = 'insert into viettelpost_district (city_id,vt_province_id,vt_district_id,name,position) values(?,?,?,?,?)';
                $write->query($sql, array($cityId, $city['ProvinceId'], $district['DistrictId'], $district['Name'], 0));
                $districtId = $write->lastInsertId();

                //ward
                $wardData = file_get_contents('http://api.vtp.vn/api/ward?district='.$district['DistrictId']);
                $wards = $helper->jsonDecode($wardData);
                foreach ($wards['_embedded']['ward'] as $ward){
                    $sql = 'insert into viettelpost_ward (vt_ward_id,district_id,vt_district_id,name,position) values(?,?,?,?,?)';
                    $write->query($sql, array( $ward['WardId'],$districtId, $district['DistrictId'], $ward['Name'], 0));
                }
            }
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f location.php 
USAGE;
    }
}

$shell = new Mage_Shell_Compiler();
$shell->run();
