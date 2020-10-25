jQuery(document).ready(function() {
	
	jQuery('.flows-wl-setting-tabs').on('click', '.flows-wl-tab', function(e) {
		e.preventDefault();
		var id = jQuery(this).attr('href');
		jQuery(this).siblings().removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.flows-wl-setting-tabs-content .flows-wl-setting-tab-content').removeClass('active');
		jQuery('.flows-wl-setting-tabs-content').find(id).addClass('active');
	});
	
	// Color Picker.
	if ( 'undefined' !== typeof jQuery.fn.wpColorPicker ) {
			// Add Color Picker to all inputs that have 'dm-color-picker' class.
			jQuery( '.flows-wl-color-picker' ).wpColorPicker();
	}
		

	jQuery(document).on("click",".flows_upload_image_button",function() {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = jQuery(this);
		wp.media.editor.send.attachment = function(props, attachment) {
		jQuery(button).parent().prev().attr('src', attachment.url);
		jQuery(button).prev().val(attachment.id);
		wp.media.editor.send.attachment = send_attachment_bkp;
		}
		wp.media.editor.open(button);
		return false;
	});
		
	// The "Remove" button (remove the value from input type='hidden')
	jQuery(document).on("click",".flows_remove_image_button",function() {
		var answer = confirm('Are you sure?');
		if (answer == true) {
			var src = jQuery(this).parent().prev().attr('data-src');
			jQuery(this).parent().prev().attr('src', src);
			jQuery(this).prev().prev().val('');
		}
		return false;
	});


});
 
