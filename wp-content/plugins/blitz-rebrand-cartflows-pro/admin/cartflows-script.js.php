jQuery(document).ready(function() {

<?php

if ( isset( $rebranding['cartflows_small_logo'] ) && !empty( $rebranding['cartflows_small_logo'])  ) {
$image_attributes = wp_get_attachment_image_src( $rebranding['cartflows_small_logo'] );
$sm_logo = $image_attributes[0];
?>
	jQuery(".wcf-templates-popup-content span.wcf-cartflows-logo-img").hide();
	jQuery(".wcf-templates-popup-content .wcf-template-logo-wrap").prepend("<img id='small-cartflows-logo' src='<?php echo $sm_logo; ?>' />");

<?php
}


if ( isset( $rebranding['cartflows_logo'] ) && !empty( $rebranding['cartflows_logo'])  ) {
$image_attributes = wp_get_attachment_image_src( $rebranding['cartflows_logo'] );
$logo = $image_attributes[0];
?>
	jQuery(".wcf-menu-page-header .wcf-title img.wcf-logo").attr("src","<?php echo $logo; ?>");
	jQuery(".wcf-menu-page-header .wcf-title img.wcf-logo").show();

<?php
}

if ( isset( $rebranding['flows_remove_learn_how'] )  && 'on' == $rebranding['flows_remove_learn_how'] )  { ?>

	jQuery('.step-type-filter-links option'). each(function(){
		
		var steps = jQuery(this).val();
		console.log(steps);
		
	})

<?php } ?>


<?php 

	$current = get_current_screen();
	$postType = $current->post_type;

	if ( isset( $rebranding['flows_title'] )  && !empty($rebranding['flows_title']) && isset($postType) && $postType == 'cartflows_flow' )  { ?> 

	var metaTitle = jQuery('#wcf-sandbox-settings h2 span').html();
	
	var createFlowbtnText = jQuery('#wcf-remote-content #wcf-start-from-scratch a.button-hero').html();
	if (typeof createFlowbtnText  !== "undefined") {

		var newcreateFlowbtnText = createFlowbtnText.replace("Flow", "<?php echo $rebranding['flows_title']; ?>");
		jQuery('#wcf-remote-content #wcf-start-from-scratch a.button-hero').html(newcreateFlowbtnText);
	}
	
	if (typeof metaTitle  !== "undefined") {

		var newMetaTitle = metaTitle.replace("Flow", "<?php echo $rebranding['flows_title']; ?>");
		jQuery('#wcf-sandbox-settings h2 span').html(newMetaTitle);
		jQuery('#wcf-sandbox-settings h2 span').show();
	
	}
	

<?php }  else if ( isset( $rebranding['flows_title'] )  && !empty($rebranding['flows_title']) && isset($postType) && $postType == 'cartflows_step' )  { ?>
	
	var btnText = jQuery('a.button-hero').html();
	var backText = jQuery('a.wcf-back-to-flow-edit').text();
	if (typeof btnText  !== "undefined") {

		var newbtnText = btnText.replace("Flow", "<?php echo $rebranding['flows_title']; ?>");
		jQuery('a.button-hero').html(newbtnText);
	}
	if (typeof backText  !== "undefined") {

		var newbackText = backText.replace("Flow", "<?php echo $rebranding['flows_title']; ?>");
		jQuery('a.wcf-back-to-flow-edit').html(newbackText);
	}
	
	
<?php }

	if ( isset( $rebranding['plugin_name'] )  && !empty($rebranding['plugin_name']) && isset($postType) && $postType == 'cartflows_flow' )  { ?>

	jQuery( document ).on( 'click', '.page-title-action:first', function (event) {

		setTimeout(function(){ 
			
			if(jQuery('body #wcf-remote-flow-importer').hasClass('open')){
			
				if(jQuery('body #wcf-remote-flow-importer #wcf-remote-content .wcf-page-builder-notice p').length){

					var replace_str2 = jQuery('body #wcf-remote-flow-importer #wcf-remote-content .wcf-page-builder-message p:nth-of-type(2)').html().replace(/CartFlows/g,'<?php echo $rebranding['plugin_name']; ?>');
					jQuery('body #wcf-remote-flow-importer #wcf-remote-content .wcf-page-builder-message p:nth-of-type(2)').html(replace_str2);
				
				}
			}
		
		}, 1000);
		
	});
	

<?php } ?>

    if(jQuery('body .cartflows-dismissible-notice p').length) {

		var replace_str1 = jQuery('body .cartflows-dismissible-notice p').html().replace(/CartFlows/g,'<?php echo $rebranding['plugin_name']; ?>');
		jQuery('body .cartflows-dismissible-notice p').html(replace_str1);
				
	}
	
	
});

