jQuery(document).ready(function() {

<?php if ( isset( $rebranding['plugin_name'] ) && !empty( $rebranding['plugin_name'])  ) : ?>



<?php 
	
if( isset($_GET['page'])) {

	if( $_GET['page'] == 'fluent_forms'  ) {
	
?>
	setTimeout(function(){

		if(jQuery('body .ff_form_wrap .fluent_form_intro').length) {
			
			var introHeading = jQuery('body .ff_form_wrap .fluent_form_intro h1').html().replace(/Fluent Forms/g,'<?php echo $rebranding['plugin_name']; ?>');
			jQuery('body .ff_form_wrap .fluent_form_intro h1').html(introHeading);		
			
			var introText = jQuery('body .ff_form_wrap .fluent_form_intro p').html().replace(/Fluent Forms/g,'<?php echo $rebranding['plugin_name']; ?>');
			jQuery('body .ff_form_wrap .fluent_form_intro p').html(introText);

		}
	}, 1000);

<?php	} else if( $_GET['page'] == 'fluent_form_add_ons'  ) {
	
?>					
	var replace_str1 = jQuery('body .add_on_modules .addon_body').each(function() {
		
		var addonText = jQuery(this).html().replace(/WP Fluent Forms/g,'<?php echo $rebranding['plugin_name']; ?>');
		var addonText = addonText.replace(/WPFluentForms/g,'<?php echo $rebranding['plugin_name']; ?>');
		var addonText = addonText.replace(/Fluent Forms/g,'<?php echo $rebranding['plugin_name']; ?>');
		jQuery(this).html(addonText);
		
	});
	
	var replace_str1 = jQuery('body .add_on_modules .module_title').html().replace(/WP Fluent Forms/g,'<?php echo $rebranding['plugin_name']; ?>');
	jQuery('body .add_on_modules .module_title').html(replace_str1);

	
<?php } else if (  $_GET['page'] == 'fluent_forms_settings') { ?>

	var currentURL = window.location.href;
	var currentURL1 = currentURL.split("page=");
	
	if(currentURL1[1] == 'fluent_forms_settings#re_captcha' ) {
		setTimeout(function(){

			var doc_str = jQuery('body .ff_global_settings_option .setting_header p:nth-of-type(1)').html().replace(/Fluent Forms/g,'<?php echo $rebranding['plugin_name']; ?>');
			jQuery('body .ff_global_settings_option .setting_header p:nth-of-type(1)').html(doc_str);
		
		}, 1000);
	}
			
	jQuery(document).on("click",".ff_settings_sidebar ul.ff_settings_list li:nth-of-type(2)",function() {
		
		setTimeout(function(){
			
			var doc_str = jQuery('body .ff_global_settings_option .setting_header p:nth-of-type(1)').html().replace(/Fluent Forms/g,'<?php echo $rebranding['plugin_name']; ?>');
			jQuery('body .ff_global_settings_option .setting_header p:nth-of-type(1)').html(doc_str);
					
		}, 1000);
	
	});
	
<?php } else if (  $_GET['page'] == 'fluent_forms_docs') { ?>
	
	var replace_str4 = jQuery('.ff_documentaion_wrapper .ff_block.block_1_3:nth-of-type(4) p').html().replace(/Fluent Form/g,'<?php echo $rebranding['plugin_name']; ?>');
	jQuery('.ff_documentaion_wrapper .ff_block.block_1_3:nth-of-type(4) p').html(replace_str4);
	
<?php } ?>

<?php } ?>


<?php endif; ?>


});
