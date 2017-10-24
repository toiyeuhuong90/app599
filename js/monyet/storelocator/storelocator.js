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
 
var Storelocator = Class.create();
Storelocator.prototype = {
	initialize: function(updateStoreUrl){
		this.updateStoreUrl = updateStoreUrl;
	},
	
	setUrl : function (url)
	{
		this.updateStoreUrl = url;
	},

	updateStore: function(){	
		var storeId;
		
		storeId = $('storeId').value;
	
		var url = this.updateStoreUrl;
	   
		url = url + 'store_id/' + storeId;
	   
		var request = new Ajax.Request(url,{method: 'get', onFailure: ""}); 
		
		if($('storelocator-method') != null){
			$('storelocator-method').style.display = 'block';
		}
		if($('selectedStore') != null)
		{
			var selected_store = $('selectedStore').value;
			
			if($('store_'+ selected_store) != null)
			{
				$('store_'+ selected_store).style.display = 'none';
			}		
			
			if($('store_'+ storeId) != null)
			{
				$('store_'+ storeId).style.display = 'block';
				$('selectedStore').value = storeId;
			}		
		}

	},
	
	selectStoreShipping : function(is_storelocator)
	{
		var url = this.updateStoreUrl;	
		
		if(is_storelocator == true)
			url += 'is_storelocator/1';
		else
			url += 'is_storelocator/2';
		
		var request = new Ajax.Request(url,{method: 'get', onFailure: ""}); 			
	}
	
}

