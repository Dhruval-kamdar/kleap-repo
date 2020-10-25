<div class="fluent-wl-settings-header">
	<h3>
		<?php 
		if( is_plugin_active(BZFLUENT_FFPRO_PLUGIN_FILE) ) {
			_e('Rebrand Fluent Forms Pro', 'bzfluent');
		} else {
			_e('Rebrand Fluent Forms', 'bzfluent');	
		}
	
		?>
	</h3>
</div>
<div class="fluent-wl-settings-wlms">

	<div class="fluent-wl-settings">
		<form method="post" id="form" enctype="multipart/form-data">

			<?php wp_nonce_field( 'fluent_wl_nonce', 'fluent_wl_nonce' ); ?>

			<div class="fluent-wl-setting-tabs-content">

				<div id="fluent-wl-branding" class="fluent-wl-setting-tab-content active">
					<h3 class="bzfluent-section-title"><?php esc_html_e('Branding', 'bzfluent'); ?></h3>
					<p><?php esc_html_e('You can white label the plugin as per your requirement.', 'bzfluent'); ?></p>
					<table class="form-table fluent-wl-fields">
						<tbody>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="fluent_wl_plugin_name"><?php esc_html_e('Plugin Name', 'bzfluent'); ?></label>
								</th>
								<td>
									<input id="fluent_wl_plugin_name" name="fluent_wl_plugin_name" type="text" class="regular-text" value="<?php echo $branding['plugin_name']; ?>" placeholder="" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="fluent_wl_plugin_desc"><?php esc_html_e('Plugin Description', 'bzfluent'); ?></label>
								</th>
								<td>
									<input id="fluent_wl_plugin_desc" name="fluent_wl_plugin_desc" type="text" class="regular-text" value="<?php echo $branding['plugin_desc']; ?>"/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="fluent_wl_plugin_author"><?php esc_html_e('Developer / Agency', 'bzfluent'); ?></label>
								</th>
								<td>
									<input id="fluent_wl_plugin_author" name="fluent_wl_plugin_author" type="text" class="regular-text" value="<?php echo $branding['plugin_author']; ?>"/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="fluent_wl_plugin_uri"><?php esc_html_e('Website URL', 'bzfluent'); ?></label>
								</th>
								<td>
									<input id="fluent_wl_plugin_uri" name="fluent_wl_plugin_uri" type="text" class="regular-text" value="<?php echo $branding['plugin_uri']; ?>"/>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row" valign="top">
									<label for="fluent_wl_primary_color"><?php esc_html_e('Primary Color', 'bzfluent'); ?></label>
								</th>
								<td>
									<input id="fluent_wl_primary_color" name="fluent_wl_primary_color" type="text" class="fluent-wl-color-picker" value="<?php echo $branding['primary_color']; ?>" />
								</td>
							</tr>
							
														
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="fluent_menu_icon"><?php esc_html_e('Menu Icon', 'bzfluent'); ?></label>
								</th>
								<td>
									<input class="regular-text" name="fluent_menu_icon" id="fluent_menu_icon" type="text" value="<?php if(isset($branding['fluent_menu'])) { echo $branding['fluent_menu']; } ?>" />
									<input class="button dashicons-picker" type="button" value="Choose Icon" data-target="#fluent_menu_icon" />
								</td>
							</tr>
							
						 	<!-- <tr valign="top">
								<th scope="row" valign="top">
									<label for="fluent_wl_logo"><?php// esc_html_e('Logo', 'bzfluent'); ?></label>
								</th>
								<td>
								<?php 
									
									//~ $default_image = plugins_url('uploads/NoImage.png', __FILE__);

									//~ if ( isset( $branding['fluent_logo'] )  && $branding['fluent_logo'] != '' ) {
										//~ $image_attributes = wp_get_attachment_image_src( $branding['fluent_logo'] );
										//~ $src = $image_attributes[0];
										//~ $value = $branding['fluent_logo'];
									//~ } else {
										//~ $src = $default_image;
										//~ $value = '';
									//~ }
								?>
									<div class="bzfluent upload">
										<img data-src="<?php //echo $default_image; ?>" src="<?php //echo $src; ?>" />
										<div class="btns">
											<input type="hidden" name="fluent_wl_logo" id="fluent_wl_logo" value="<?php echo $value;?>" />
											<button type="button" class="bzfluent_upload_image_button button">Upload</button>
											<button type="button" class="bzfluent_remove_image_button button">&times;</button>
										</div>
									</div>
								</td>
							</tr> -->
									
							 <tr valign="top">
									<th scope="row" valign="top">
										<label for="fluent_wl_hide_sign_tab"><?php echo esc_html_e('Hide Signature Addon Tab', 'bzrap'); ?></label>
									</th>
									<td>
										<input id="fluent_wl_hide_sign_tab" name="fluent_wl_hide_sign_tab" type="checkbox" class="" value="on" <?php echo isset( $branding['fluent_hide_sign_tab '] ) && 'on' == $branding['fluent_hide_sign_tab'] ? ' checked="checked" ' : ''; ?>/>
									</td>
							 </tr>
									
							 <tr valign="top">
									<th scope="row" valign="top">
										<label for="fluent_wl_hide_license_tab"><?php echo esc_html_e('Hide License Tab', 'bzrap'); ?></label>
									</th>
									<td>
										<input id="fluent_wl_hide_license_tab" name="fluent_wl_hide_license_tab" type="checkbox" class="" value="on" <?php echo isset( $branding['fluent_hide_license_tab '] ) && 'on' == $branding['fluent_hide_license_tab'] ? ' checked="checked" ' : ''; ?>/>
									</td>
							 </tr>
									
							 <tr valign="top">
									<th scope="row" valign="top">
										<label for="fluent_wl_hide_help_menu"><?php echo esc_html_e('Hide Get Help Menu', 'bzrap'); ?></label>
									</th>
									<td>
										<input id="fluent_wl_hide_help_menu" name="fluent_wl_hide_help_menu" type="checkbox" class="" value="on" <?php echo isset( $branding['fluent_hide_help_menu'] ) && 'on' == $branding['fluent_hide_help_menu'] ? ' checked="checked" ' : ''; ?>/>
									</td>
							 </tr>
									
							 <tr valign="top">
									<th scope="row" valign="top">
										<label for="fluent_wl_hide_modules_menu"><?php echo esc_html_e('Hide Modules Menu', 'bzrap'); ?></label>
									</th>
									<td>
										<input id="fluent_wl_hide_modules_menu" name="fluent_wl_hide_modules_menu" type="checkbox" class="" value="on" <?php echo isset( $branding['fluent_hide_modules_menu'] ) && 'on' == $branding['fluent_hide_modules_menu'] ? ' checked="checked" ' : ''; ?>/>
									</td>
							 </tr>
									
							 <tr valign="top">
									<th scope="row" valign="top">
										<label for="fluent_wl_hide_tools_menu"><?php echo esc_html_e('Hide Tools Menu', 'bzrap'); ?></label>
									</th>
									<td>
										<input id="fluent_wl_hide_tools_menu" name="fluent_wl_hide_tools_menu" type="checkbox" class="" value="on" <?php echo isset( $branding['fluent_hide_tools_menu'] ) && 'on' == $branding['fluent_hide_tools_menu'] ? ' checked="checked" ' : ''; ?>/>
									</td>
							 </tr>
									
		
							<?php 
								if( is_plugin_active(BZFLUENT_FFPRO_PLUGIN_FILE) ) {
								} else { ?>
								
								<tr valign="top">
									<th scope="row" valign="top">
										<label for="fluent_wl_hide_pro_menu"><?php echo esc_html_e('Hide Go Pro Menu', 'bzrap'); ?></label>
									</th>
									<td>
										<input id="fluent_wl_hide_pro_menu" name="fluent_wl_hide_pro_menu" type="checkbox" class="" value="on" <?php echo isset( $branding['fluent_hide_pro_menu'] ) && 'on' == $branding['fluent_hide_pro_menu'] ? ' checked="checked" ' : ''; ?>/>
									</td>
								</tr>
								
							<?php }	?>
							
						</tbody>
					</table>
				</div>
				
				<div class="fluent-wl-setting-footer">
					<p class="submit">
						<input type="submit" name="fluent_submit" id="fluent_save_branding" class="button button-primary bzfluent-save-button" value="<?php esc_html_e('Save Settings', 'bzfluent'); ?>" />
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
