<?php
	$action = Elementor_blitzlabel_Plugin::get_form_action();
?>

<?php if ( 'off' == $branding['hide_settings'] || empty( $branding['hide_settings'] ) ) : ?>

<div class="wrap"><h1 class="wp-heading-inline"><?php echo esc_html( __( 'Elementor Rebranding', 'blitz_controls' ) );?></h1></div>


<div class="el-blitz-settings-wrap">
	<?php Elementor_blitzlabel_Plugin::render_update_message(); ?>

	<div class="el-blitz-settings">
		<form method="post" id="<?php echo Elementor_blitzlabel_Plugin::$settings_page; ?>-form" action="<?php echo $action; ?>">

			<?php wp_nonce_field( 'el_blitz_nonce', 'el_blitz_nonce' ); ?>

			<div class="el-blitz-setting-tabs">

				<a href="#el-blitz-branding" class="el-blitz-tab active"><?php _e('Rebranding', 'el-blitzlabel'); ?></a>

				<a href="#el-blitz-ghost-mode" class="el-blitz-tab"><?php _e('Secret Mode', 'el-blitzlabel'); ?></a>
			</div>

			<div class="el-blitz-setting-tabs-content">


				<div id="el-blitz-branding" class="el-blitz-setting-tab-content active">
					<h3 class="el-blitzlabel-section-title"><?php esc_html_e('Rebranding', 'el-blitzlabel'); ?></h3>
					<p><?php esc_html_e('You can Rebrand the plugin as per your requirement with Blitz Rebrand Control Pro for Elementor Plugin.', 'el-blitzlabel'); ?></p>
					<table class="form-table el-blitz-fields">
						<tbody>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_plugin_name"><?php esc_html_e('Plugin Name', 'el-blitzlabel'); ?></label>
								</th>
								<td>
									<input id="el_blitz_plugin_name" name="el_blitz_plugin_name" type="text" class="regular-text" value="<?php echo $branding['plugin_name']; ?>" placeholder="" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_plugin_desc"><?php esc_html_e('Plugin Description', 'el-blitzlabel'); ?></label>
								</th>
								<td>
									<input id="el_blitz_plugin_desc" name="el_blitz_plugin_desc" type="text" class="regular-text" value="<?php echo $branding['plugin_desc']; ?>"/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_plugin_author"><?php esc_html_e('Developer / Agency', 'el-blitzlabel'); ?></label>
								</th>
								<td>
									<input id="el_blitz_plugin_author" name="el_blitz_plugin_author" type="text" class="regular-text" value="<?php echo $branding['plugin_author']; ?>"/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_plugin_uri"><?php esc_html_e('Website URL', 'el-blitzlabel'); ?></label>
								</th>
								<td>
									<input id="el_blitz_plugin_uri" name="el_blitz_plugin_uri" type="text" class="regular-text" value="<?php echo $branding['plugin_uri']; ?>"/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_edit_with_text"><?php echo sprintf( esc_html__('Edit with %s - Text', 'el-blitzlabel'), 'Elementor'); ?></label>
								</th>
								<td>
									<input id="el_blitz_edit_with_text" name="el_blitz_edit_with_text" type="text" class="regular-text" value="<?php echo ( isset( $branding['edit_with_text'] ) ) ? $branding['edit_with_text'] : ''; ?>" placeholder="<?php echo sprintf( esc_html__('Edit with %s', 'el-blitzlabel'), 'Elementor'); ?>"/>
								</td>
							</tr>
							<?php if ( ! defined( 'ELEMENTOR_PRO_PLUGIN_BASE' ) ) { ?>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_disable_pro"><?php esc_html_e('Disable Pro Upgrade Messages', 'el-blitzlabel'); ?></label>
								</th>
								<td>
									<input id="el_blitz_disable_pro" name="el_blitz_disable_pro" type="checkbox" class="" value="on" <?php echo 'on' == $branding['disable_pro'] ? ' checked="checked" ' : ''; ?>/>
								</td>
							</tr>
							<?php } ?>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_hide_external_links"><?php echo sprintf( esc_html__('Hide %s External Links', 'el-blitzlabel'), 'Elementor' ); ?></label>
								</th>
								<td>
									<input id="el_blitz_hide_external_links" name="el_blitz_hide_external_links" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_external_links'] ) && 'on' == $branding['hide_external_links'] ? ' checked="checked" ' : ''; ?>/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_hide_logo"><?php esc_html_e('Hide Logo', 'el-blitzlabel'); ?></label>
								</th>
								<td>
									<input id="el_blitz_hide_logo" name="el_blitz_hide_logo" type="checkbox" class="" value="on" <?php echo 'on' == $branding['hide_logo'] ? ' checked="checked" ' : ''; ?>/>
								</td>
							</tr>
							<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_admin_menu"><?php echo sprintf( esc_html__('Hide %s from Menu', 'el-blitzlabel'), 'Elementor' ); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_admin_menu" name="el_blitz_hide_admin_menu" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_admin_menu'] ) && 'on' == $branding['hide_admin_menu'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_my_templates"><?php esc_html_e('Hide My Templates', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_my_templates" name="el_blitz_hide_my_templates" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_my_templates'] ) && 'on' == $branding['hide_my_templates'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_settings_page"><?php esc_html_e('Hide Settings Page', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_settings_page" name="el_blitz_hide_settings_page" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_settings_page'] ) && 'on' == $branding['hide_settings_page'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_custom_fonts"><?php esc_html_e('Hide Custom Fonts', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_custom_fonts" name="el_blitz_hide_custom_fonts" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_custom_fonts'] ) && 'on' == $branding['hide_custom_fonts'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_custom_icons"><?php esc_html_e('Hide Custom Icons', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_custom_icons" name="el_blitz_hide_custom_icons" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_custom_icons'] ) && 'on' == $branding['hide_custom_icons'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_role_manager"><?php esc_html_e('Hide Role Manager', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_role_manager" name="el_blitz_hide_role_manager" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_role_manager'] ) && 'on' == $branding['hide_role_manager'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_tools"><?php esc_html_e('Hide Tools', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_tools" name="el_blitz_hide_tools" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_tools'] ) && 'on' == $branding['hide_tools'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_system_info"><?php esc_html_e('Hide System Info', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_system_info" name="el_blitz_hide_system_info" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_sys_info'] ) && 'on' == $branding['hide_sys_info'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_knowledge_base"><?php esc_html_e('Hide Knowledge Base', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_knowledge_base" name="el_blitz_hide_knowledge_base" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_knowledge_base'] ) && 'on' == $branding['hide_knowledge_base'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_license_page"><?php esc_html_e('Hide License Page', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_license_page" name="el_blitz_hide_license_page" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_license_page'] ) && 'on' == $branding['hide_license_page'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_general_settings"><?php esc_html_e('Hide Getting Started', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_getting_started" name="el_blitz_hide_getting_started" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_getting_started'] ) && 'on' == $branding['hide_getting_started'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						
						<!--- Hide/Show Page & Blocks in Library Settings -->
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_library_blocks"><?php esc_html_e('Hide Blocks', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_library_blocks" name="el_blitz_hide_library_blocks" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_library_blocks'] ) && 'on' == $branding['hide_library_blocks'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_library_pages"><?php esc_html_e('Hide Pages', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_library_pages" name="el_blitz_hide_library_pages" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_library_pages'] ) && 'on' == $branding['hide_library_pages'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_library_pro_templates"><?php esc_html_e('Hide Pro Templates', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_library_pro_templates" name="el_blitz_hide_library_pro_templates" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_library_pro_templates'] ) && 'on' == $branding['hide_library_pro_templates'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_export_template"><?php esc_html_e('Hide Export Template', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								
								<?php
									
									//~ print_r($branding);	
								
								 ?>
								
								<input id="el_blitz_hide_export_template" name="el_blitz_hide_export_template" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_library_export_template'] ) && 'on' == $branding['hide_library_export_template'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_import_template"><?php esc_html_e('Hide Import Template', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_import_template" name="el_blitz_hide_import_template" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_library_import_template'] ) && 'on' == $branding['hide_library_import_template'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_notices"><?php esc_html_e('Hide Notices', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_notices" name="el_blitz_hide_notices" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_notices'] ) && 'on' == $branding['hide_notices'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<!--- /Hide/Show Page & Blocks in Library Settings -->
							
							<!--<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_primary_color"><?php //esc_html_e('Primary Color', 'el-blitzlabel'); ?></label>
								</th>
								<td>
									<input id="el_blitz_primary_color" name="el_blitz_primary_color" type="text" class="el-blitz-color-picker" value="<?php //echo $branding['primary_color']; ?>" />
								</td>
							</tr>-->
						</tbody>
					</table>
								
				</div>


				<div id="el-blitz-ghost-mode" class="el-blitz-setting-tab-content">
					<h3><?php _e('Secret Mode', 'el-blitzlabel'); ?></h3>
					<p>
						<?php echo sprintf( esc_html__('You can hide both %s and Blitz Rebrand Control Pro for Elementor plugin to prevent your client from seeing these settings.', 'el-blitzlabel'), 'Elementor' ); ?>
						<?php echo sprintf( __( '<br />Save this URL %s to re-enable the plugins and settings. Alternatively, you can deactivate the Blitz Rebrand Control Pro for Elementor plugin and activate it again.', 'el-blitzlabel'), '<code style="font-size: 12px;">' . Elementor_blitzlabel_Plugin::get_form_action('&el_blitz_reset=1') . '</code>' ); ?>
					</p>
					<table class="form-table el-blitzlabel-branding">
						<tr valign="top">
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="el_blitz_hide_settings"><?php esc_html_e('Hide Blitz Rebrand Pro Options', 'el-blitzlabel'); ?></label>
								</th>
								<td>
									<input id="el_blitz_hide_settings" name="el_blitz_hide_settings" type="checkbox" class="" value="on" <?php echo 'on' == $branding['hide_settings'] ? ' checked="checked" ' : ''; ?>/>
								</td>
							</tr>
							<th scope="row" valign="top">
								<label for="el_blitz_hide_el_plugin"><?php echo sprintf( esc_html__('Hide %s Plugin', 'el-blitzlabel'), 'Elementor' ); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_el_plugin" name="el_blitz_hide_el_plugin" type="checkbox" class="" value="on" <?php echo isset( $branding['hide_el_plugin'] ) && 'on' == $branding['hide_el_plugin'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="el_blitz_hide_plugin"><?php esc_html_e('Hide Blitz Rebrand Control Pro for Elementor Plugin', 'el-blitzlabel'); ?></label>
							</th>
							<td>
								<input id="el_blitz_hide_plugin" name="el_blitz_hide_plugin" type="checkbox" class="" value="on" <?php echo 'on' == $branding['hide_plugin'] ? ' checked="checked" ' : ''; ?>/>
							</td>
						</tr>
					</table>
				</div>

				<div class="el-blitz-setting-footer">
					<p class="submit">
						<input type="submit" name="submit" id="el_save_branding" class="button button-primary el-blitzlabel-button" value="<?php esc_html_e('Save Settings', 'el-blitzlabel'); ?>" />
					</p>
				</div>
			</div>
		</form>
	</div>
</div>

<?php else : ?>
<div class="notice notice-info" style="margin-top: 50px;">
	<?php $reset_url = Elementor_blitzlabel_Plugin::get_form_action('&el_blitz_reset=1'); ?>
	<p><?php echo sprintf( __('<a href="%s">Click here</a> to reset the plugin interface OR save this URL to reset anytime %s'), $reset_url, '<code>' . $reset_url . '</code>' ); ?></p>
</div>
<?php endif; ?>
