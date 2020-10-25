function th_save_activate_license(event){

    event.preventDefault(event);
    
    var licenseKey = jQuery(event.srcElement.form.template_hero_elementor_license_key).val();
    
    jQuery.ajax({
        url: templatehero_ajax_obj.ajaxurl,
        type: 'post',
        data: {
            'action':'th_update_license_options',
            'th_form_license_input' : licenseKey,
            'th_create_token_security': templatehero_ajax_obj.th_create_token_security,
        },
        success: function( response ) {
            
            var response = JSON.parse(response);
            if(response.status == 'error'){

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


   