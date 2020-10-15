jQuery(document).ready(function($) {
    
    jQuery("a#template-hero-api-token-refresh,#template-hero-api-token-refresh").click(function(e){

        e.preventDefault();

        var token =  $(this).data( "token" )
        var library_id =  $(this).data( "library_id" )
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Refresh Token',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.value) {

                $.ajax({
                    url: templatehero_ajax_obj.ajaxurl,
                    type: 'post',
                    data: {
                        'action':'refreshJwtToken',
                        'user_id': templatehero_ajax_obj.user_id,
                        'token': token,
                        'library_id': library_id,
                        'th_create_token_security': templatehero_ajax_obj.th_create_token_security,
                    },
                    success: function( response ) {
        
                        var tokenObj = JSON.parse( response );
                        if( !tokenObj.success ) {

                            Swal.fire({
                                title: 'Error',
                                text: tokenObj.message,
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                           
                              })
        
                        } else {
        
                            Swal.fire({
                                title: 'Success',
                                text: tokenObj.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                           
                            })
                        } 
                    },
                
                });
            } //endif
        })
    });

    jQuery("#template-hero-api-token-remove").click(function(e){

        e.preventDefault();
        var library_id =  $(this).data( "library_id" )
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover your token!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Remove Token',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.value) {

                $.ajax({
                    url: templatehero_ajax_obj.ajaxurl,
                    type: 'post',
                    data: {
                        'action':'removeTokenTransient',
                        'user_id': templatehero_ajax_obj.user_id,
                        'library_id': library_id,
                        'th_create_token_security': templatehero_ajax_obj.th_create_token_security,
                    },
                    success: function( response ) {
        
                        var tokenObj = JSON.parse( response );
                        if( !tokenObj.success ){

                            Swal.fire({
                                title: 'Error',
                                text: tokenObj.message,
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                           
                            })
        
                        } else {
        
                            Swal.fire({
                                title: 'Success',
                                text: tokenObj.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                           
                            })
                        } 
                    },
                });
            } //endif
        })
    });

});


/**
 * Gets Client Library Secret
 * @param {*} event 
 */
function getClientLibrarySecret(event) {

    event.preventDefault();
    var row_id = event.srcElement.dataset.id;

    jQuery.ajax({
        url: templatehero_ajax_obj.ajaxurl,
        type: 'post',
        data: {
            'action':'thGetClientLibrarySecret',
            'user_id': templatehero_ajax_obj.user_id,
            'row_id': row_id,
            'th_create_token_security': templatehero_ajax_obj.th_create_token_security,

        },
        success: function( response ) {
          
            var tokenObj = JSON.parse( response );
            jQuery('#th-client-row-'+tokenObj.row_id+' .th-table-secret').text(tokenObj.client_secret);
            
        },
    });
};


/**
 * Delets Client Library
 * @param {*} event 
 */
var row_id = 0;
function thbb_deleteLibrary(event) {

    event.preventDefault();
    row_id = event.srcElement.dataset.id;
    jQuery.ajax({
        url: templatehero_ajax_obj.ajaxurl,
        type: 'post',
        data: {
            'action':'thDeleteClientLibrary',
            'user_id': templatehero_ajax_obj.user_id,
            'row_id': row_id,
            'th_create_token_security': templatehero_ajax_obj.th_create_token_security,
        },
        success: function( response ) {
            var tokenObj = JSON.parse( response );
            if ( tokenObj == row_id ) {
                jQuery( '#th-client-row-'+row_id ).remove();
            }
            
        },
    });
};


/**
 * Delets Client Library
 * @param {*} event 
 */
var row_id = 0;
function the_wu_activateLibrary(event) {

    event.preventDefault();
    row_id  = event.srcElement.dataset.id;
    context = event.srcElement.value;
    str1    = "#th-activate-library";
    var res = str1.concat(row_id);
    var message = 'Library will be activated!';
    var message_2 = 'Activate Library.'
    if ( context == 'Deactivate' ) {
        message = 'Library will be deactivated!';
        message_2 = 'Deactivate Library.'
    }
    Swal.fire({
        title: 'Are you sure?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: message_2,
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.value) {

            jQuery.ajax({
                url: templatehero_ajax_obj.ajaxurl,
                type: 'post',
                data: {
                    'action':'the_activate_library',
                    'user_id': templatehero_ajax_obj.user_id,
                    'template_hero_active_library': row_id,
                    'th_create_token_security': templatehero_ajax_obj.th_create_token_security,
                    'context': context
                },
                success: function( response ) {
    
                    var tokenObj = JSON.parse( response );
                    if( !tokenObj.success ){
                        Swal.fire({
                            title: 'Error',
                            text: tokenObj.message,
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                       
                        })
                        
                    } else {
                        if ( context == 'Deactivate' ) {
                            jQuery(res).html("Activate");
                            jQuery(res).val("Activate");
                            jQuery(res).removeClass('btn-danger');
                            jQuery(res).addClass('btn-success');
                        } else {
                            jQuery(res).html("Deactivate");
                            jQuery(res).val("Deactivate");
                            jQuery(res).removeClass('btn-success');
                            jQuery(res).addClass('btn-danger');

                        }
                        Swal.fire({
                            title: 'Success',
                            text: tokenObj.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                       
                          })
                    } 
                },
            });
        } //endif
    });
};