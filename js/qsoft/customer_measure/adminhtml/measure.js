function getDataFile() {
    var reloadUrl = $('url_mapping').value;
    var fd = new FormData(document.getElementById("edit_form"));

    jQuery.ajax({
        url: reloadUrl,
        type: "POST",
        data: fd,
        dataType: 'json',
        enctype: 'multipart/form-data',
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        // beforeSend: function () {
        //     jQuery('span.loading-data').show();
        // },
        success: function (data) {
            if(data.content){
                jQuery('#mapping-fields').html(data.content);
            }
            if(data.error){
                jQuery('#mapping-fields').html('');
                alert(data.error);
            }
        }
    });

}

function showGallery(id){
    jQuery('.image-gallery').hide();
    jQuery('#' + id).show();
}

function viewHistory(id){
    jQuery('.measurement-content').hide();
    jQuery('#history-' + id).show();
}

function deleteHistory(id, tag, reloadUrl){
    if(confirm('Are you sure want remove this?')){
        jQuery.ajax({
            url: reloadUrl,
            dataType: 'json',
            enctype: 'multipart/form-data',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            beforeSend: function () {
                jQuery('#loading-mask').show();
            },
            success: function (data) {
                jQuery('#loading-mask').hide();
                jQuery('#' + id).val('');
                jQuery(tag).remove();
            }
        });
    }

}