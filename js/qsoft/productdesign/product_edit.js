document.observe('dom:loaded', function(){
    getProductBg();


    $('product_design_type').observe('change', function() {
        jQuery('.product-bg-image').remove();

        getProductBg();
    });
});

function updateCurrentImg(element, id){
    var img = window.document.getElementById('preview_' + id);
    img.src = element.value;
}

function getOptionBg(){
    var elm = $('product_design_type');
    var value = [];
    var options = elm.options;
    var k=0;
    for(var i=0; i < options.length; i++){
        if(options[i].selected){
            value[k] = {id:options[i].value, name:options[i].text};
            k++;
        }
    }
    return value;
}

function getProductBg(){
    var value = getOptionBg();
    var url = jQuery('#product_edit_form').attr('action');
    url = url.replace('abpr0/catalog_product/save','qsproductdesign/adminhtml_ajax/bgdesign');
    new Ajax.Request(url, {
        type: 'post',
        parameters:{
            'data': JSON.stringify(value)
        },
        beforeSend: function () {

        },
        onSuccess: function(response) {
            var json = response.responseText.evalJSON(true);

            jQuery('#product_design_type').parent().parent().parent().append(json.content);
        }
    });
}

function setZoomInValue(elm){
    var options = elm.options;
    var v = '';
    for(var i=0; i< options.length; i++){
        if(options[i].selected){
            if(v==''){
                v = options[i].value;
            }else{
                v += ',' + options[i].value;
            }
        }
    }
    $(elm.id + '_real').value = v;
}

function in_array(needle, haystack) {
    for (var key = 0; key < haystack.length; key++) {
        if (needle == haystack[key]) {
            return true;
        }
    }

    return false;
}

function setIsDefaultBg(tag) {
    $$('.input-radio').each(function(elm){
        elm.checked = false;
    });
    tag.checked = true;
}
