<div class="ee-mb-setting-body">
	<div id="ee-mb-banner" class="ee-mb-banner-sticky">
		<h2>
			<span><?php _e( 'Property Settings', 'elementor-extensions' ); ?></span>
		</h2>
	</div>
	
	<?php if(isset($_GET['saved']) && $_GET['saved'] == 1){ ?>
	
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible" style="margin-left:0; margin-bottom:10px;"> 
			<p><strong><?php _e( 'Settings saved.', 'elementor-extensions' ); ?></strong></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'elementor-extensions' ); ?></span></button>
			<div></div>
		</div>
	
	<?php }

		if(!empty($_SESSION['error_message'])):
			foreach($_SESSION['error_message'] as $key => $error_msg):
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e($error_msg, 'elementor-extensions'); ?></p>
				</div>
				<?php	
			endforeach;
			unset($_SESSION['error_message']);
		elseif(!empty($_SESSION['success_message'])):
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php _e('Changes successfully saved.', 'elementor-extensions'); ?></p>
			</div>
			<?php
		endif;
	?>
	
	<div class="ee-mb-setting-content">
	
		<div class="ee-mb-tab-contain">
			<button class="tablink" data-page="general"> <?php _e('General', 'elementor-extensions'); ?></button>

			<button class="tablink" data-page="single_page"> <?php _e('Single Page', 'elementor-extensions'); ?></button>
			
			<button class="tablink" data-page="agent"> <?php _e('Agent', 'elementor-extensions'); ?></button>
		</div>

		<?php 	
			foreach (glob(__DIR__."/tabs/*.php") as $tab_file):
				include_once($tab_file);
			endforeach;
		?>
	</div>
</div>