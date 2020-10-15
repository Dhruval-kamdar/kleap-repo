(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
/**
 * Delets Client Library
 * @param {*} event 
 */
var row_id = 0;
function the_wu_activateLibraryplan(event) {

    event.preventDefault();
	row_id   = event.srcElement.dataset.id;
	plan_id  = event.srcElement.dataset.plan;
    context = event.srcElement.value;
    str1    = "#th-activate-library";
    var res = str1.concat(row_id);
    Swal.fire({
        title: 'Are you sure?',
        text: 'Library will be activated!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Activate Library.',
        cancelButtonText: 'Cancel'
      }).then( ( result ) => {
        if ( result.value ) {

            jQuery.ajax({
                url: templatehero_ajax_obj.ajaxurl,
                type: 'post',
                data: {
                    'action':'the_wu_activateLibraryplan',
                    'user_id': templatehero_ajax_obj.user_id,
                    'template_hero_active_library': row_id,
                    'th_create_token_security': templatehero_ajax_obj.th_create_token_security,
					'context': context,
					'plan_id' : plan_id
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