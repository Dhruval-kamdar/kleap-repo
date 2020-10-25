<div class="wrap acf-settings-wrap">
	
	<h1><?php echo $page_title; ?></h1>
	
	<form id="post" method="post" name="post">
		
		<?php 
		
		// render post data
		acf_form_data(array(
			'screen'	=> 'options',
			'post_id'	=> $post_id,
		));
		
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		
		?>
		
		<div id="poststuff">
			
			<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
				
				<div id="postbox-container-1" class="postbox-container">
					
					<?php do_meta_boxes('acf_options_page', 'side', null); ?>
						
				</div>
				
				<div id="postbox-container-2" class="postbox-container">
					
					<?php
					$screen = get_current_screen();
					$current_screen = $screen->id;
					?> 
					
					<?php
					$blog_id = get_current_blog_id(); //current blog ID
					switch_to_blog($blog_id);
					$errorAlert = get_option('error_message');
					$layoutChanged = get_field('layout_changed', 'option');
					restore_current_blog();
					if( $errorAlert != '' && $current_screen == 'toplevel_page_content-pro-settings') {
					?>
					<p class="errorMessage"><b>Content Editor Pro:</b> <?php echo $errorAlert; ?></p>
					
					<?php } else if($errorAlert == '' && $current_screen == 'toplevel_page_content-pro-settings' && ($layoutChanged != '0') && is_super_admin()) { 			
					?>
					
<!--
					<form  data-confirm="New updates found in Layout, Do you want to Load?" method="POST">
-->
						<button class="loadupdatedLayout" type="click" data-confirm="New updates found in Layout, Do you want to Load?">Load Latest Layout</button>
<!--
					</form>
-->
					<script type="text/javascript">
					jQuery(document).on('click', '.loadupdatedLayout', function(e){
						if(confirm(jQuery(this).data('confirm'))){
							jQuery.ajax({
							  type: 'post',
							  data: {loadlayout: 'yes'},
							  success: function(response){
								  window.location.reload();
							  }
							});
						} else {
							jQuery.ajax({
							  type: 'post',
							  data: {loadlayout: 'no'},
							  success: function(response){
								// Code
							  }
							});
						}
						return false;

					});
					</script>
				<?php do_meta_boxes('acf_options_page', 'normal', null); ?>

					<?php } else { ?>
					
					<?php do_meta_boxes('acf_options_page', 'normal', null); ?>
					
					<?php } ?>
					
				</div>
			
			</div>
			
			<br class="clear">
		
		</div>
		
	</form>
	
</div>
