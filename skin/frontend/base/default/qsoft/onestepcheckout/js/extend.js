;
//DUMMY FOR EE CHECKOUT
var checkout =  {
		steps : new Array("login", "billing", "shipping", "shipping_method", "payment", "review"),
		
		gotoSection: function(section){
			Qsoft.Onestepcheckout.backToOpc();
		},
		accordion:{
			
		}
};


Qsoft.Onestepcheckout.prepareExtendPaymentForm =  function(){
	$j_opc('.opc-col-left').hide();
	$j_opc('.opc-col-center').hide();
	$j_opc('.opc-col-right').hide();
	$j_opc('.opc-menu p.left').hide();	
	$j_opc('#checkout-review-table-wrapper').hide();
	$j_opc('#checkout-review-submit').hide();
	
	$j_opc('.review-menu-block').addClass('payment-form-full-page');
	
};

Qsoft.Onestepcheckout.backToOpc =  function(){
	$j_opc('.opc-col-left').show();
	$j_opc('.opc-col-center').show();
	$j_opc('.opc-col-right').show();
	$j_opc('#checkout-review-table-wrapper').show();
	$j_opc('#checkout-review-submit').show();
	
	
	
	//hide payments form
	$j_opc('#payflow-advanced-iframe').hide();
	$j_opc('#payflow-link-iframe').hide();
	$j_opc('#hss-iframe').hide();

	
	$j_opc('.review-menu-block').removeClass('payment-form-full-page');
	
	Qsoft.Onestepcheckout.saveOrderStatus = false;
	
};



Qsoft.Onestepcheckout.Plugin = {
		
		observer: {},
		
		
		dispatch: function(event, data){
				
			
			if (typeof(Qsoft.Onestepcheckout.Plugin.observer[event]) !="undefined"){
				
				var callback = Qsoft.Onestepcheckout.Plugin.observer[event];
				callback(data);
				
			}
		},
		
		event: function(eventName, callback){
			Qsoft.Onestepcheckout.Plugin.observer[eventName] = callback;
		}
};

/** 3D Secure Credit Card Validation - CENTINEL **/
Qsoft.Onestepcheckout.Centinel = {
	init: function(){
		Qsoft.Onestepcheckout.Plugin.event('savePaymentAfter', Qsoft.Onestepcheckout.Centinel.validate);
	},
	
	validate: function(){
		var c_el = $j_opc('#centinel_authenticate_block');
		if(typeof(c_el) != 'undefined' && c_el != undefined && c_el){
			if(c_el.attr('id') == 'centinel_authenticate_block'){
				Qsoft.Onestepcheckout.prepareExtendPaymentForm();
			}
		}
	},
	
	success: function(){
		var exist_el = false;
		if(typeof(c_el) != 'undefined' && c_el != undefined && c_el){
			if(c_ell.attr('id') == 'centinel_authenticate_block'){
				exist_el = true;
			}
		}
		
		if (typeof(CentinelAuthenticateController) != "undefined" || exist_el){
			Qsoft.Onestepcheckout.backToOpc();
		}
	}
	
};


function toggleContinueButton(){}//dummy

$j_opc(document).ready(function(){
	Qsoft.Onestepcheckout.Centinel.init();
});
