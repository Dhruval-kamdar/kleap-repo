
function the_wu_save_activate_license( event ) {

    event.preventDefault( event );
    
    var licenseKey = jQuery(event.srcElement.form.the_wu_license_key).val();
    
    jQuery.ajax({
        url: the_wu_ajax_obj.ajaxurl,
        type: 'post',
        data: {
            'action':'the_wu_update_license_options',
            'th_form_license_input' : licenseKey,
            'th_create_token_security': the_wu_ajax_obj.the_wu_create_token_security,
        },
        success: function( response ) {
            
            var response = JSON.parse(response);

            if( response.status == 'error' ) {

                Swal.fire({
                    title: 'Error',
                    text: response.message,
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                  })

            } else {

                Swal.fire({
                    title: 'License Activated',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'Confirm',
                  })
            } 
        },
    });
}


   