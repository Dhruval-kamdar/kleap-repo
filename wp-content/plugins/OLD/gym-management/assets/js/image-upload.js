jQuery(document).ready(function($) {
	
	var file_frame;
	// this is system logo..
	jQuery('#upload_user_avatar_button').click(function( event ){
		
		event.preventDefault();
		if ( file_frame ) {
		    file_frame.open();
		     return;
		}		
		file_frame = wp.media.frames.file_frame = wp.media({
		    title: jQuery( this ).data( 'uploader_title' ),
		    button: {
		        text: jQuery( this ).data( 'uploader_button_text' ),
		    },
		     multiple: false  
		});

		file_frame.on( 'select', function() {
		    attachment = file_frame.state().get('selection').first().toJSON();
			
			file=attachment.url
			var get_file_extension = file.substr( (file.lastIndexOf('.') +1) );

			if($.inArray(get_file_extension, ['jpg','jpeg','gif','png','bmp'])== -1)
			{
			//alert('JPEG,JPG,GIF,PNG,BMP File allowed ,'+get_file_extension+' file not allowed');
			alert(language_translate1.allow_file_alert);								
			//Only (JPEG,JPG,GIF,PNG,BMP) File allowed. pdf file not allowed.
			
			// file_frame.open();
			return false; 
			}
			else
			{	
		    jQuery("#gmgt_user_avatar_url").val(attachment.url);
		    $('#upload_user_avatar_preview img').attr('src',attachment.url);
		      // Do something with attachment.id and/or attachment.url here
			}
		});
		file_frame.open();
	});
	
	
	
	var file_frames
	// for cover page..
	jQuery('.upload_user_cover_button').click(function( event ){
	    event.preventDefault();
		if ( file_frames ) {
		    file_frames.open();
		    return;
		}

		file_frames = wp.media.frames.file_frames = wp.media({
		    title: jQuery( this ).data( 'uploader_title' ),
		    button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
		    },
		      multiple: false  // Set to true to allow multiple files to be selected
		});

		  file_frames.on( 'select', function() {
		    attachment = file_frames.state().get('selection').first().toJSON();
			file=attachment.url
			var get_file_extension = file.substr( (file.lastIndexOf('.') +1) );

			if($.inArray(get_file_extension, ['jpg','jpeg','gif','png','bmp'])== -1)
			{
				alert(language_translate1.allow_file_alert);								
			// file_frame.open();
			return false; 
			}
			else
			{	
		    jQuery("#gmgt_gym_background_image").val(attachment.url);
		    $('#upload_gym_cover_preview img').attr('src',attachment.url);
            }
		     
		    });		   
		    file_frames.open();
	});
	
	
	var file_frame1;
	// this is system logo..
	jQuery('.upload_user_avatar_button').click(function( event ){
		
		event.preventDefault();
		if ( file_frame1 ) {
		    file_frame1.open();
		     return;
		}		
		file_frame1 = wp.media.frames.file_frame1 = wp.media({
		    title: jQuery( this ).data( 'uploader_title' ),
		    button: {
		        text: jQuery( this ).data( 'uploader_button_text' ),
		    },
		     multiple: false  
		});
		
		file_frame1.on( 'select', function() {
		    attachment = file_frame1.state().get('selection').first().toJSON();
			file=attachment.url
			var get_file_extension = file.substr( (file.lastIndexOf('.') +1) );

			if($.inArray(get_file_extension, ['jpg','jpeg','gif','png','bmp'])== -1)
			{
				alert(language_translate1.allow_file_alert);								
			// file_frame.open();
			return false; 
			}
			else
			{	
		    jQuery(".gmgt_user_avatar_url").val(attachment.url);
		    $('.upload_user_avatar_preview img').attr('src',attachment.url);
		      // Do something with attachment.id and/or attachment.url here
			}
		});
		file_frame1.open();
	});
	
});