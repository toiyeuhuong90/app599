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
 
class Monyet_Storelocator_Model_Gmap extends Varien_Object
{
	const GURL = "http://maps.google.com/maps/geo?q=";
	const GDISTANCE_URL = "http://maps.google.com/maps/nav?q=";
	const GPARAM = "&output=xml&sensor=true&key=";
	const FOMAT_ADDRESS = "{{street}},{{city}},{{region}} {{zipcode}},{{country}}";
	
	public function getCoordinates($address = null)
	{
		$address = $address ? $address : $this->getAddress();
		
		$this->setAddress($address);
		
		if(! $address)
			return;
		
		$address = $this->getFormatedAddress();
		
		$url = self::GURL;	
		$url .= $address;
		$url .= self::GPARAM;
		$url .= $this->getGmapKey();		

		$loop = 0;
		try{
			do{
				$result = Mage::helper('storelocator')->getResponseBody($url);

				$xml = simplexml_load_string($result);
				
				$status_code = (string) $xml->Response->Status->code;
				
				if($status_code == '620')
				{
					usleep(100000);
				}
				$loop++;
			}while($status_code == '620' && $loop<2);
		
			if($status_code != '200')
				return array();
			$coordinates = (string)$xml->Response->Placemark->Point->coordinates;

			$coordinates = explode(',',$coordinates);

			return array('lat'=>$coordinates[1],'lng'=>$coordinates[0]);
		} catch(Exception $e){
			return false;
		}
	}
	
	public function getDistance($spoint,$dpoint)
	{
		if(!isset($spoint['lat']) || !isset($spoint['lng'])
			 || !isset($dpoint['lat']) || !isset($dpoint['lng']))
			
			return false;
		
		$url = self::GDISTANCE_URL;	
		$url .= 'from:'.$spoint['lat'].','.$spoint['lng'].'%20to:'.$dpoint['lat'].','.$dpoint['lng'];
		$url .= self::GPARAM;
		$url .= $this->getGmapKey();
		
		$loop = 0;
		do{
			$result = Mage::helper('storelocator')->getResponseBody($url);
			
			$result = str_replace('\\u','%23',$result);
			
			$result =  Zend_Json_Decoder::decode($result);
			
			$status_code = $result['Status']['code'];

			$loop++;
		}while($status_code == '620' && $loop<1);
		
		if($status_code != '200')
			return false;
		
		$distance = $result['Directions']['Distance']['meters'];
		$distance = intval($distance);
		
		return $distance;
	}	
	
	public function getFormatedAddress()
	{
		$address = $this->getAddress();
		
		$formatedaddress = self::FOMAT_ADDRESS;
		$formatedaddress = str_replace('{{street}}',$address['street'],$formatedaddress);
		$formatedaddress = str_replace('{{city}}',$address['city'],$formatedaddress);
		$formatedaddress = str_replace('{{region}}',$address['region'],$formatedaddress);
		$formatedaddress = str_replace('{{zipcode}}',$address['zipcode'],$formatedaddress);
		$formatedaddress = str_replace('{{country}}',$address['country'],$formatedaddress);
		
		$formatedaddress = str_replace(' ','%20',$formatedaddress);

		return $formatedaddress;
	}
	
	public function getGmapKey()
	{
		return Mage::getStoreConfig("general/storelocator/gkey");
	}
}